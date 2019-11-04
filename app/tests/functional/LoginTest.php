<?php


namespace functional;

use Exception;
use Tests\TestCase\FunctionalTestCase;

class LoginTest extends FunctionalTestCase
{
    /**
     * @throws Exception
     */
    public function testShouldShowLoginForm()
    {
        $this->setGetRequest('/login');
        $this->invokeApp();
        $response = $this->getResponse();

        $this->assertEquals(200, $response->getCode());

        $contents = trim($response->getContents());
        $this->assertEquals('<form name="login-form" method="post"><input name="login"/><input name="password"/><button name="submit" value="save" id="login-button">save</button></form>', $contents);
    }
}