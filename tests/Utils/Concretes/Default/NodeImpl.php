<?php

namespace Tests\Utils\Concretes\Default;

use Aco\Components\Abstracts\Node;

class NodeImpl extends Node
{
    private int $att;

    public function __construct(int $att) {
        $this->att = $att;

        parent::__construct();
    }

    public function getAtt(): int {
        return $this->att;
    }
}