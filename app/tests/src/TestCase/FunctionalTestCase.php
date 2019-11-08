<?php


namespace Tests\TestCase;

use App\App;
use Container\Process\ProcessContext;
use Exception;
use PHPUnit\Framework\TestCase;
use Response\Response;

abstract class FunctionalTestCase extends TestCase
{
    /** @var ProcessContext $processContext */
    protected $processContext;

    public function setGetRequest(string $getRequest)
    {
        $this->parseAndSetParameters($getRequest);

        $_SERVER['REQUEST_URI'] = $getRequest;
        $_SERVER['REQUEST_METHOD'] = 'get';
    }

    public function setPostRequest(string $postRequest, array $postData)
    {
        $this->parseAndSetParameters($postRequest);

        $_SERVER['REQUEST_URI'] = $postRequest;
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST = $postData;
    }

    private function parseAndSetParameters(string $getRequest): void
    {
        $query = parse_url($getRequest, PHP_URL_QUERY);
        parse_str($query, $_GET);
    }

    public function invokeApp()
    {
        ob_start();
        $app = new App();
        $dependencyTree = include APP_DIR . '/config/app.php';
        $app->__invoke($dependencyTree);
        $this->processContext = $app->getProcessContext();
        ob_end_clean();
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function getResponse(): Response
    {
        $response = $this->processContext->get('response');
        if($response instanceof Response)
        {
            return $response;
        }

        throw new Exception($response);
    }
}