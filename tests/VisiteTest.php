<?php

namespace App\Tests;

use App\Entity\Visite;
use PHPUnit\Framework\TestCase;

class VisiteTest extends TestCase {
    
    public function testGetDatecreationString() {
        $visite = new Visite();
        $visite->setDateCreation(new \DateTime("2025-02-28"));
        $this->assertEquals("28/02/2025", $visite->getDatecreationString());
    }
}
