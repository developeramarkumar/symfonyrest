<?php

namespace AppBundle\Controller;




use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends FOSRestController
{
    /**
     * @Route("/get", methods={"GET","HEAD"})
     */
    public function getAction()
    {

        $restresult = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        if ($restresult === null) {
          return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function createAction(Request $request)
    {
         $data = new User;
           $name = $request->get('name');
           $role = $request->get('role');
         if(empty($name) || empty($role))
         {
           return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE); 
         } 
          $data->setName($name);
          $data->setRole($role);
          $em = $this->getDoctrine()->getManager();
          $em->persist($data);
          $em->flush();
          return new View("User Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Route("/edit/{id}", methods={"PUT"})
     */
    public function editAction($id, Request $request)
    {

      $data = new User;
      $name = $request->get('name');
      $role = $request->get('role');
      $sn = $this->getDoctrine()->getManager();
      $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

      if (empty($user)) {
         return new View("user not found", Response::HTTP_NOT_FOUND);
      } 

      elseif(!empty($name) && !empty($role)){
         $user->setName($name);
         $user->setRole($role);
         $sn->flush();
         return new View("User Updated Successfully", Response::HTTP_OK);
      }

      elseif(empty($name) && !empty($role)){
         $user->setRole($role);
         $sn->flush();
         return new View("role Updated Successfully", Response::HTTP_OK);
      }

      elseif(!empty($name) && empty($role)){
       $user->setName($name);
       $sn->flush();
       return new View("User Name Updated Successfully", Response::HTTP_OK); 
      }
      else return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE); 
    }

    /**
     * @Route("/delete/{id}",methods={"DELETE"})
     */
    public function deleteAction($id)
    {
     $data = new User;
     $sn = $this->getDoctrine()->getManager();
     $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
     if (empty($user)) {
         return new View("user not found", Response::HTTP_NOT_FOUND);
     }
     else {
     $sn->remove($user);
     $sn->flush();
     }
     return new View("deleted successfully", Response::HTTP_OK);
    }

}
