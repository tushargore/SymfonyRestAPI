<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
class UserController extends Controller
{

	 /**
     * @ApiDoc(
     *  resource=true,
     *  description="All User Information",
     *)
     */
    public function showAllAction()
    {  
        $data = $this->getDoctrine()->getManager();
        $value = $data->getRepository('AppBundle:Users')->findAll(); 
        
        $user_values = array();
        
        foreach($value as $user) {
           $user_values[] = array(
            'id' => $user->getId(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
           );
        }

        return new JsonResponse($user_values,200);
    }

     /**
     * @ApiDoc(
     *  resource=true,
     *  description="Given User Information",
     * )
     */
    public function showAction($id)
    {  
        $data = $this->getDoctrine()->getManager();
        $user_id = $this->getDoctrine()->getRepository('AppBundle:Users')->find($id);
        $user_data = array();
           if($user_id == null) {
              $msg="No data found for this ID";
              return new JsonResponse($msg,404); 
        } else {
           $user_data[]=$user_id->getId();
           $user_data[]=$user_id->getName();
           $user_data[]=$user_id->getUsername();
           $user_data[]=$user_id->getPassword();

          return new JsonResponse($user_data,200); 
      }
    }  

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Delete Given User ID"
     * )
     */
    public function deleteAction($id)
    { 
        $deleteuser = $this->getDoctrine()->getManager();  
            if(!$id){
            $msg="Please Enter valid ID";
        } else {
        	$userid = $this->getDoctrine()->getRepository('AppBundle:Users')->find($id);
        	 if($uid != null) {
               $deleteuser->remove($userid);
               $deleteuser->flush();
               $msg="Data deleted for this ID";
               return new JsonResponse($msg,200);
           } else {
              $msg="No Data for this ID";
              return new JsonResponse($msg,404);               
            }
  
         }
    }
  /**
     * @ApiDoc(
     *  resource=true,
     *  description="Create New User")
     * @RequestParam(name="name", description="name", default="") 
     * @RequestParam(name="username", description="username", default="") 
       * @RequestParam(name="password", description="password", default="") 
     */
public function newAction(Request $request)
{
	$name = $request->request->get('name');
	$username = $request->request->get('username');
	$password = $request->request->get('password');

	$user = new Users();
 if (empty($name) || empty($username) || empty($password)) {
              $msg="Field not empty";
              return new JsonResponse($msg,400); 
        }else {
	$user->setName($name);
	$user->setUsername($username);
	$user->setPassword($password);

	$create = $this->getDoctrine()->getManager();
	$create->persist($user);
	$create->flush();
	$msg= "Data Inserted";
	return new JsonResponse($msg, 200);
}
 }

/**
     * @ApiDoc(
     *  resource=true,
	 *description="Update Given User")
	 * @RequestParam(name="name", description="name", default="") 
     * @RequestParam(name="username", description="username", default="") 
      * @RequestParam(name="password", description="password", default="") 
     */
    public function editAction(Request $request, $id)
     {   
        $editUser = $this->getDoctrine()->getManager();
        
        $edit_id = $this->getDoctrine()->getRepository('AppBundle:Users')->find($id);
        if(!$edit_id){
               $msg="No ID found";
               return new JsonResponse($msg,404); 
            } else {
         $user_data=$this->getRequest()->request->all();
         foreach($user_data as $key=>$value){
          	if($key=="name") 
          		{
          			$edit_id->setName($value);
          		}
         	 if($key=="username") 
         	 	{
         	 		$edit_id->setUsername($value);
         	 	}
         	 if($key=="password") 
         	 	{
         	 		$edit_id->setPassword($value);}
               }
             $editUser->persist($edit_id);
            $editUser->flush();
            $msg="Data updated for this ID";
            return new JsonResponse($msg,200); 
          } 
      }
       
}
