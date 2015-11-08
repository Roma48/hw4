<?php

namespace Tests;

use Entity\EntityManager;
use User\User;
use Composer\Autoload;


class EntityManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testParseEntity()
    {
        $mock = $this->getMockBuilder('Entity\EntityManager')
            ->setMethods(array('insert'))
            ->getMock();

        $mock->method('insert')
            ->willReturn(1);

        $user = new User();
        $user->setCreatedAt('1111');
        $user->setUserName('Roma');

        $testEntity = new EntityManager();

        $this->assertInstanceOf('User\User' , $user);

        $parsedEntity = $testEntity->entityParse($user);

        $this->assertCount(2, $parsedEntity);

        $this->assertArraySubset( ['entity' => 'User',
            'properties' => [
                'id' => null,
                'userName' => 'Roma',
                'createdAt' => '1111',
                'updatedAt' => null,
                'deletedAt' => null
            ]] , $parsedEntity);
    }

    public function testPrepareSql()
    {
        $queryArr = [
            'entity' => 'User',
            'properties' => [
                'id' => null,
                'userName' => 'Roma',
                'createdAt' => '1111',
                'updatedAt' => null,
                'deletedAt' => null
            ]
        ];

        $entityManager = new EntityManager();

        $this->assertEquals("INSERT INTO user (`userName`,`createdAt`) VALUES ('Roma','1111')",
            $entityManager->prepareSql($queryArr)
        );

    }

}