<?php

namespace Teebb\SBAdmin2Bundle\Tests\Admin;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdminTest extends KernelTestCase
{

    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();

    }

    public function testMyFirstCase()
    {

        $articleAdmin = self::$container->get('App\Admin\ArticleAdmin');

        $this->assertSame('Article', $articleAdmin->getLabel());

        $categoryAdmin = self::$container->get('App\Admin\CategoryAdmin');

        $this->assertTrue($categoryAdmin->hasChild('App\Admin\ArticleAdmin'));
    }

}