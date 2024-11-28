<?php

namespace App\Tests\Authentication;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    private function clearTestDb(): void
    {
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);

        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        // Drop and recreate the schema
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    function testUserRegister(): void
    {
        $client = static::createClient();

        $this->clearTestDb();

        $client->request(
            'POST',
            '/api/user/register',
            content: json_encode(
                [
                    'username' => 'john_test',
                    'email' => 'john@email.com',
                    'password' => '123'
                ]
            )
        );

        $this->assertResponseStatusCodeSame(200);

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($content);
        $this->assertContains('token', array_keys($content), 'Token not generated');
        $this->assertNotEmpty($content['token'], 'Token parameter is empty');
    }

    function testUserRegisterExceptions(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/user/register',
            content: json_encode(
                [
                    'username' => 'john_test',
                    'email' => 'john@email.com',
                    'password' => '123'
                ]
            )
        );

        $this->assertResponseStatusCodeSame(401);

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertContains('errors', array_keys($content), "Errors not trigerred");
    }
}
