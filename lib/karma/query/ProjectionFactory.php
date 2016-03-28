<?php
namespace watoki\karma\query;

interface ProjectionFactory {

    /**
     * @param mixed $event
     * @return string
     */
    public function applyMethod($event);

    /**
     * @param object $query
     * @return object
     */
    public function buildProjection($query);
}