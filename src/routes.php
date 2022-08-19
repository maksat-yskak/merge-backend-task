<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

$app = new \Slim\App;

// Get user list
$app->get('/api/users', function (Request $request, Response $response) {
    $sql = "SELECT * FROM user";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($users));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {

        $response->getBody()->write(json_encode($e->getMessage()));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    } finally {
        $db = null;
    }
});


// Get user by $id
$app->get('/api/user/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $validation = v::alnum(" !\"#$%&'()*+,-./:;<=>?@[\]^_`{|}~")->validate($id);
    if (!$validation) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in id"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    $sql = "SELECT * FROM user WHERE id = '$id'";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($user));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {

        $response->getBody()->write(json_encode($e->getMessage()));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    } finally {
        $db = null;
    }
});

// Create user
$app->post('/api/user/add', function (Request $request, Response $response) {

    $id = $request->getParsedBody()['id'];
    $name = $request->getParsedBody()['name'];
    $email = $request->getParsedBody()['email'];
    $job = $request->getParsedBody()['job'];
    $cv = $request->getParsedBody()['cv'];
    $user_image = $request->getParsedBody()['user_image'];
    $experiences = $request->getParsedBody()['experience'];

    if(!v::alnum(" !\"#$%&'()*+,-./:;<=>?@[\]^_`{|}~")->validate($id)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in id"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::alpha()->validate($name)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in name"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::email()->validate($email)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in email"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::alpha(' ')->validate($job)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in job"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::url()->anyOf(v::endsWith('.doc'), v::endsWith('docx'), v::endsWith('.pdf'))->validate($cv)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in cv"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::url()->anyOf(v::endsWith('.png'), v::endsWith('jpg'), v::endsWith('jpeg'))->validate($user_image)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in user_image"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    foreach($experiences as $value) {
        if(!v::alpha(' ')->validate($value['job_title'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in job_title"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }

        if(!v::countryCode()->validate($value['location'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in location"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }

        if(!v::alnum("d/m/Y")->validate($value['start_date'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in start_date"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }

        if(!v::alnum("d/m/Y")->validate($value['end_date'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in end_date"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }
    }

    $experience = json_encode($experiences, true);

    $sql = "INSERT INTO user (id, name, email, job, cv, user_image, experience) VALUE (:id, :name, :email, :job, :cv, :user_image, :experience)";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':job', $job);
        $stmt->bindParam(':cv', $cv);
        $stmt->bindParam(':user_image', $user_image);
        $stmt->bindParam(':experience', $experience);

        $result = $stmt->execute();

        $response->getBody()->write(json_encode($result));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {

        $response->getBody()->write(json_encode($e->getMessage()));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    } finally {
        $db = null;
    }
});

// Update user
$app->put('/api/user/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $name = $request->getParsedBody()['name'];
    $email = $request->getParsedBody()['email'];
    $job = $request->getParsedBody()['job'];
    $cv = $request->getParsedBody()['cv'];
    $user_image = $request->getParsedBody()['user_image'];
    $experiences = $request->getParsedBody()['experience'];

    if(!v::alpha()->validate($name)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in name"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::email()->validate($email)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in email"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::alpha()->validate($job)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in job"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::url()->anyOf(v::endsWith('.doc'), v::endsWith('docx'), v::endsWith('.pdf'))->validate($cv)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in cv"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    if(!v::url()->anyOf(v::endsWith('.png'), v::endsWith('jpg'), v::endsWith('jpeg'))->validate($user_image)) {
        $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in user_image"]));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }

    foreach($experiences as $value) {
        if(!v::alpha()->validate($value['job_title'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in job_title"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }

        if(!v::countryCode()->validate($value['location'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in location"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }

        if(!v::alnum("d/m/Y")->validate($value['start_date'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in start_date"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }

        if(!v::alnum("d/m/Y")->validate($value['end_date'])) {
            $response->getBody()->write(json_encode(['code'=>400, 'message'=>"bad format parameter in end_date"]));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(400);
        }
    }

    $experience = json_encode($experiences, true);

    $sql = "UPDATE user SET
                name = :name,
                email = :email,
                job = :job,
                cv = :cv,
                user_image = :user_image,
                experience = :experience
            WHERE id = '$id'";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':job', $job);
        $stmt->bindParam(':cv', $cv);
        $stmt->bindParam(':user_image', $user_image);
        $stmt->bindParam(':experience', $experience);

        $result = $stmt->execute();

        $response->getBody()->write(json_encode($result));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {

        $response->getBody()->write(json_encode($e->getMessage()));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    } finally {
        $db = null;
    }
});

// Delete user
$app->delete('/api/user/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM user WHERE id = '$id'";

    try {
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);

        $result = $stmt->execute();

        $response->getBody()->write(json_encode($result));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {

        $response->getBody()->write(json_encode($e->getMessage()));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    } finally {
        $db = null;
    }
});