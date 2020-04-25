<?php
/**
 * Date: 25/04/2020
 * Time: 23:03
 */

namespace Tests\Controller\Questions;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionsWriterTest extends WebTestCase
{
    public function testInvalidContentType()
    {
        $client = static::createClient();
        $client->request('POST', '/questions', [], [], ['CONTENT_TYPE' => 'application/xml']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testInvalidJsonRequest()
    {
        $client = static::createClient();
        $badFile = file_get_contents(__DIR__.'/incorrect_json.json');
        $client->request('POST', '/questions', [], [], ['CONTENT_TYPE' => 'application/json'], $badFile);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testInvalidChoicesNumber()
    {
        $client = static::createClient();
        $badFile = file_get_contents(__DIR__.'/incorrect_choices.json');
        $client->request('POST', '/questions', [], [], ['CONTENT_TYPE' => 'application/json'], $badFile);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testValid()
    {
        $client = static::createClient([],['test' => true]);
        $correctJson = file_get_contents(__DIR__.'/correct_json.json');
        $client->request('POST', '/questions', [], [], ['CONTENT_TYPE' => 'application/json'], $correctJson);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
