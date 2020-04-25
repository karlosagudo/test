<?php
/**
 * Date: 25/04/2020
 * Time: 12:26
 */

namespace Tests\Controller\Questions;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionsReaderTest extends WebTestCase
{
    public function testRead()
    {
        $client = static::createClient();
        $client->request('GET', '/questions?lang=en');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testReadCorrectText()
    {
        $client = static::createClient();
        $client->request('GET', '/questions?lang=en');

        $jsonfile = json_decode(file_get_contents(__DIR__.'/../../../docs/questions.json'), true);
        $contentArr = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($contentArr[0]['text'], $jsonfile[0]['text']);
    }

    public function testReadCorrectTranslated()
    {
        $client = static::createClient();
        $client->request('GET', '/questions?lang=es');

        $contentArr = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($contentArr[0]['text'], '¿Cuál es la capital de Luxemburgo?');
    }
}
