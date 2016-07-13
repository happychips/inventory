<?php
namespace happy\inventory\app;

use happy\inventory\AcquireMaterials;
use happy\inventory\AddCostumer;
use happy\inventory\AddSupplier;
use happy\inventory\ConsumeMaterial;
use happy\inventory\DeliverProduct;
use happy\inventory\ListAcquisitions;
use happy\inventory\ListCostumers;
use happy\inventory\ListLinkedConsumptions;
use happy\inventory\ListLinkedProductConsumptions;
use happy\inventory\ListMaterials;
use happy\inventory\ListProducts;
use happy\inventory\ListSuppliers;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\SupplierIdentifier;
use happy\inventory\ProduceProduct;
use happy\inventory\projecting\CurrentInventory;
use happy\inventory\projecting\CurrentStock;
use happy\inventory\projecting\LinkedConsumptions;
use happy\inventory\projecting\LinkedProductConsumptions;
use happy\inventory\ReceiveDelivery;
use happy\inventory\RegisterMaterial;
use happy\inventory\RegisterProduct;
use happy\inventory\SetLinkedConsumption;
use happy\inventory\SetMaterialCategory;
use happy\inventory\ShowHistory;
use happy\inventory\ShowInventory;
use happy\inventory\ShowStock;
use happy\inventory\TransformMaterial;
use happy\inventory\UpdateInventory;
use happy\inventory\UpdateStock;
use rtens\domin\delivery\web\adapters\curir\root\IndexResource;
use rtens\domin\delivery\web\menu\ActionMenuItem;
use rtens\domin\delivery\web\renderers\link\types\ClassLink;
use rtens\domin\delivery\web\renderers\tables\types\DataTable;
use rtens\domin\delivery\web\renderers\tables\types\ObjectTable;
use rtens\domin\delivery\web\WebApplication;
use rtens\domin\execution\RedirectResult;
use rtens\domin\reflection\GenericMethodAction;
use rtens\domin\reflection\GenericObjectAction;
use watoki\curir\WebDelivery;
use watoki\karma\stores\EventStore;

class Launcher {

    /** @var HttpSession */
    private $session;

    /** @var Application */
    private $app;

    /** @var string */
    private $usersFile;

    /** @var string */
    private $userDir;

    public function __construct(EventStore $events, HttpSession $session, $usersFile, $userDir) {
        $this->session = $session;
        $this->app = new Application($events, $this->session, $userDir);
        $this->usersFile = $usersFile;
        $this->userDir = $userDir;
    }

    public function run() {
        WebDelivery::quickResponse(IndexResource::class, WebApplication::init(function (WebApplication $domin) {
            $domin->setNameAndBrand('Inventory');

            $this->addActions($domin);

            $domin->renderers->add(new MyFileRenderer());

            $domin->fields->add(new IdentifierField($domin->fields, $domin->identifiers));
            $domin->fields->add(new PasswordField());

            $domin->identifiers->setProvider(MaterialIdentifier::class, function () {
                return $this->app->handle(new ListMaterials())->getMaterials();
            });
            $domin->identifiers->setProvider(AcquisitionIdentifier::class, function () {
                return $this->app->handle(new ListAcquisitions())->getAcquisitions();
            });
            $domin->identifiers->setProvider(ProductIdentifier::class, function () {
                return $this->app->handle(new ListProducts())->getProducts();
            });
            $domin->identifiers->setProvider(CostumerIdentifier::class, function () {
                return $this->app->handle(new ListCostumers())->getCostumers();
            });
            $domin->identifiers->setProvider(SupplierIdentifier::class, function () {
                return $this->app->handle(new ListSuppliers())->getSuppliers();
            });

            $domin->renderers->add(new ConsumeMaterialRenderer($this->app->handle(new ListMaterials())->getMaterials()));
        }, WebDelivery::init()));
    }

    private function addActions(WebApplication $domin) {
        if ($this->session->isLoggedIn()) {
            $this->addAction($domin, RegisterMaterial::class, 'Setup');
            $this->addAction($domin, SetMaterialCategory::class, 'Setup');
            $this->addAction($domin, RegisterProduct::class, 'Setup');
            $this->addAction($domin, AddCostumer::class, 'Setup');
            $this->addAction($domin, AddSupplier::class, 'Setup');
            $this->addAction($domin, ListLinkedConsumptions::class, 'Setup')
                ->setModifying(false)
                ->setAfterExecute(function (LinkedConsumptions $consumptions) use ($domin) {
                    return new DataTable(new ObjectTable($consumptions->getConsumptions(), $domin->types));
                });

            $this->addAction($domin, SetLinkedConsumption::class)
                ->setFill(function ($parameters) {
                    if (isset($parameters['product'])) {
                        $product = new ProductIdentifier($parameters['product']);
                        $parameters['consumptions'] = $this->app->handle(new ListLinkedProductConsumptions($product))->getConsumptions();
                    }
                    return $parameters;
                });
            $domin->links->add(new ClassLink(LinkedProductConsumptions::class,
                $this->makeActionId(SetLinkedConsumption::class),
                function (LinkedProductConsumptions $consumptions) {
                    return ['product' => $consumptions->productIdentifier()];
                }));

            $this->addAction($domin, AcquireMaterials::class, 'Material');
            $this->addAction($domin, ReceiveDelivery::class, 'Material');
            $this->addAction($domin, ConsumeMaterial::class, 'Material');
            $this->addAction($domin, UpdateInventory::class, 'Material');
            $this->addAction($domin, TransformMaterial::class, 'Material');

            $this->addAction($domin, ProduceProduct::class, 'Product');
            $this->addAction($domin, UpdateStock::class, 'Product');
            $this->addAction($domin, DeliverProduct::class, 'Product');

            $this->addAction($domin, ShowInventory::class, 'Reporting')
                ->setModifying(false)
                ->setAfterExecute(function (CurrentInventory $inventory) use ($domin) {
                    return new DataTable(new ObjectTable($inventory->getMaterials(), $domin->types));
                });
            $this->addAction($domin, ShowStock::class, 'Reporting')
                ->setModifying(false)
                ->setAfterExecute(function (CurrentStock $inventory) use ($domin) {
                    return new DataTable(new ObjectTable($inventory->getProducts(), $domin->types));
                });
            $this->addAction($domin, ShowHistory::class, 'Reporting')->setModifying(false);

            $domin->menu->add(new ActionMenuItem('Inventory', 'ShowInventory'));
            $domin->menu->add(new ActionMenuItem('Stock', 'ShowStock'));

            $domin->actions->add('Logout', (new GenericMethodAction($this, 'logout', $domin->types, $domin->parser))->generic()
                ->setModifying(false)
                ->setCaption('Logout'));
            $domin->menu->addRight(new ActionMenuItem('Logout (' . $this->session->user() . ')', 'Logout'));

            $domin->actions->add('CreateUser', (new GenericMethodAction($this, 'createUser', $domin->types, $domin->parser))->generic()
                ->setCaption('Create User'));
            $domin->groups->put('CreateUser', 'Setup');
        } else {
            $domin->actions->add('Login', (new GenericMethodAction($this, 'login', $domin->types, $domin->parser))->generic()->setCaption('Login'));
            $domin->menu->addRight(new ActionMenuItem('Login', 'Login'));
        }
    }

    private function addAction(WebApplication $domin, $class, $group = null) {
        $execute = function ($object) {
            return $this->app->handle($object);
        };

        $id = $this->makeActionId($class);
        $action = new GenericObjectAction($class, $domin->types, $domin->parser, $execute);
        $domin->actions->add($id, $action);

        if ($group) {
            $domin->groups->put($id, $group);
        }

        return $action->generic();
    }

    private function makeActionId($class) {
        return (new \ReflectionClass($class))->getShortName();
    }

    private function readUsers() {
        return json_decode(file_get_contents($this->usersFile), true);
    }

    private function saveUsers($users) {
        file_put_contents($this->usersFile, json_encode($users));
    }

    /**
     * @param string $user
     * @param Password $password
     * @param Password $passwordRepeated
     * @throws \Exception
     */
    public function createUser($user, $password, $passwordRepeated) {
        $users = $this->readUsers();

        if (isset($users[$user])) {
            throw new \Exception('User already exists');
        } else if ($password->getPassword() != $passwordRepeated->getPassword()) {
            throw new \Exception('Passwords do not match');
        }

        $users[$user] = sha1($password->getPassword());

        $this->saveUsers($users);
    }

    /**
     * @param string $user
     * @param Password $password
     * @return RedirectResult
     * @throws \Exception
     */
    public function login($user, $password) {
        $users = $this->readUsers();

        if (isset($users[$user]) && $users[$user] == sha1($password->getPassword())) {
            $this->session->login($user);
        } else {
            throw new \Exception('Invalid credentials');
        }
        return new RedirectResult('index');
    }

    /**
     * @return RedirectResult
     */
    public function logout() {
        $this->session->logout();
        return new RedirectResult('index');
    }
}