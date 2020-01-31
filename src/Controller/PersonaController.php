<?php


namespace App\Controller;


use App\Entity\Persona;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PersonaController extends AbstractController
{
    private $personaRepository;

    public function __construct( PersonaRepository $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    /**
     * @Route("/persona/{id}", methods={"GET"})
     */
    public function readOne(EntityManagerInterface $manager, int $id)
    {
        $persona = $manager->getRepository(Persona::class)->find(Persona::class, $id);
        return (is_null($persona))
            ? $this->json(["error" => "This persona doesn't exists."], 400)
            : $this->json($persona->toArray());
    }

    /**
     * @Route("/persona/", methods={"GET"})
     */
    public function readAll(EntityManagerInterface $manager)
    {
        $personas = $manager->getRepository(Persona::class)->findAll();
        $personasArray = array_map(function($persona) {
            return $persona->toArray();
        }, $personas);
        return $this->json(["personas" => $personasArray]);
    }

    /**
     * @Route("/persona", METHODS={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager) : Response
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
        $persona = new Persona();
        $persona->setAge($request->request->get("age"));
        $persona->setName($request->request->get("name"));
        $persona->setEmail($request->request->get("email"));
        $errors = $validator->validate($persona,null,[__FUNCTION__]);
        if($errors->count() > 0){
            $errorNormalizer = array();
            foreach ($errors as $error) {
                $errorNormalizer[$error->getPropertyPath()] = [];
            }
            foreach ($errors as $error) {
                array_push($errorNormalizer[$error->getPropertyPath()],$error->getMessage());
            }
            return $this->json($errorNormalizer);
        }
        $manager->persist($persona);
        $manager->flush();
        return $this->json($persona->toArray());
    }

    /**
     *@Route("/persona/{id}", methods={"PUT", "PATCH"})
     */
    public function update(ValidatorInterface $validator, Request $request, EntityManagerInterface $manager,int $id) : Response
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
        $persona = $manager->find(Persona::class, $id);
        if(is_null($persona)) {
            return $this->json(["error" => "Persona not found."], 400);
        }

        foreach ($request->request->all() as $parameterKey => $parameter) {
            /** @var string $dynamicSetter */
            $dynamicSetter = 'set'.ucfirst($parameterKey);

            method_exists($persona, $dynamicSetter) && $persona->$dynamicSetter($parameter);
        }

        $errors = $validator->validate($persona,null,[__FUNCTION__]);
        if($errors->count() > 0){
            $errorNormalizer = array();
            foreach ($errors as $error) {
                $errorNormalizer[$error->getPropertyPath()] = [];
            }
            foreach ($errors as $error) {
                array_push($errorNormalizer[$error->getPropertyPath()],$error->getMessage());
            }
            return $this->json($errorNormalizer);

        }
        $manager->persist($persona);
        $manager->flush();
        return $this->json($persona->toArray());
    }

    /**
     * @Route("/persona/{id}", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $manager, int $id) : Response
    {
        $persona = $manager->find(Persona::class, $id);
        if(is_null($persona)){
            return $this->json(["error" => "This persona doesn't exists."], 400);
        }
        $manager->remove($persona);
        $manager->flush();
        return $this->json($persona->toArray());
    }

}