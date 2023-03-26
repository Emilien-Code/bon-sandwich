<?php
    namespace lbs\front\actions;

    use Psr\Http\Message\RequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    class GetOrders {
        
        public function __invoke(Request $request, Response $response, $args): Response {


            $headers = $request->getHeaders();
            $authorization = $headers["Authorization"][0];

            $headers = ['Authorization' => $authorization];

            $client = new \GuzzleHttp\Client();
            $headers = [
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vYXV0aC5teWFwcC5uZXQiLCJhdWQiOiJodHRwOi8vYXBpLm15YXBwLm5ldCIsImlhdCI6MTY3OTY2NzMzMiwiZXhwIjoxNjc5NjcwOTMyLCJ1aWQiOiJKdWxpZW4uRHVwcmVAb3JhbmdlLmZyIiwibHZsIjoxLCJ1c2VybmFtZSI6Ikp1bGllbi5EdXByZSJ9.SeEIrWdXKXwlfrBArwR-e3dv9FiYmShdJGfpkEKb50X0Ff4ee7ZyHhkZ62hK4URzL0lwRdobYV-6ritKdRZv8g',
                'Content-Type' => 'application/json'
            ];
            echo "test";
            $request = new \GuzzleHttp\Psr7\Request('GET', 'http://api.auth/validate', $headers);
            echo "test";
            
            
            
            $res = $client->sendAsync($request)->wait();
            echo "test";
            
            echo $res->getBody();




            $response->getBody()->write(json_encode("rien"));

            return $response;
        }

    }