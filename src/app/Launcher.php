<?php
namespace happy\inventory\app;

use happy\inventory\AcquireMaterial;
use happy\inventory\ConsumeMaterial;
use happy\inventory\ListAcquisitions;
use happy\inventory\ListMaterials;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\ReceiveDelivery;
use happy\inventory\RegisterMaterial;
use happy\inventory\ShowHistory;
use happy\inventory\UpdateInventory;
use rtens\domin\delivery\web\adapters\curir\root\IndexResource;
use rtens\domin\delivery\web\WebApplication;
use rtens\domin\reflection\GenericMethodAction;
use rtens\domin\reflection\GenericObjectAction;
use watoki\curir\WebDelivery;
use watoki\karma\EventStore;
use watoki\stores\transforming\TransformerRegistryRepository;

class Launcher {

    /** @var HttpSession */
    private $session;

    /** @var Application */
    private $app;

    /** @var array */
    private $users;

    public function __construct(EventStore $events, HttpSession $session, array $users) {
        $this->session = $session;
        $this->app = new Application($events, $this->session);
        $this->users = $users;
    }

    public function run() {
        $transformerRegistry = TransformerRegistryRepository::getDefaultTransformerRegistry();
        $transformerRegistry->insert(new FileTransformer($transformerRegistry));

        WebDelivery::quickResponse(IndexResource::class, WebApplication::init(function (WebApplication $domin) {
            $domin->setNameAndBrand('Inventory');

            $this->addActions($domin);

            $domin->fields->add(new IdentifierField($domin->fields, $domin->identifiers));

            $domin->identifiers->setProvider(MaterialIdentifier::class, function () {
                return $this->app->handle(new ListMaterials())->getMaterials();
            });
            $domin->identifiers->setProvider(AcquisitionIdentifier::class, function () {
                return $this->app->handle(new ListAcquisitions())->getAcquisitions();
            });
        }, WebDelivery::init()));
    }

    private function addActions(WebApplication $domin) {
        if ($this->session->isLoggedIn()) {
            $this->addAction($domin, RegisterMaterial::class);
            $this->addAction($domin, AcquireMaterial::class);
            $this->addAction($domin, ReceiveDelivery::class);
            $this->addAction($domin, ConsumeMaterial::class);
            $this->addAction($domin, UpdateInventory::class);
            $this->addAction($domin, ShowHistory::class)->setModifying(false);
        } else {
            $domin->actions->add('Login', (new GenericMethodAction($this, 'login', $domin->types, $domin->parser))->generic()->setCaption('Login'));
        }
    }

    private function addAction(WebApplication $domin, $class) {
        $execute = function ($object) {
            return $this->app->handle($object);
        };

        $action = new GenericObjectAction($class, $domin->types, $domin->parser, $execute);
        $domin->actions->add((new \ReflectionClass($class))->getShortName(), $action);

        return $action->generic();
    }

    /**
     * @param string $user
     * @param Password $password
     * @throws \Exception
     */
    public function login($user, $password) {
        if (isset($this->users[$user]) && $this->users[$user] == $password->getPassword()) {
            $this->session->login($user);
        } else {
            throw new \Exception('Invalid credentials');
        }
    }
}