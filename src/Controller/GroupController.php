<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use App\Form\UserGroupType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Monolog\DateTimeImmutable;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/group")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/", name="group_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $group = $user->getUserGroup();
        return $this->render('group/index.html.twig', [
                'groups' => $group]
        );
    }
    //->findBy(['creator'=>$this->getUser()]

    /**
     * @Route("/new", name="group_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group->setCreatedAt(new DateTimeImmutable('now'));
            $group->setUpdatedAt(new DateTimeImmutable('now'));
            $group->setCreator($user);
            $group->addUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();


            return $this->redirectToRoute('group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('group/new.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="group_show", methods={"GET"})
     */
    public function show(Group $group): Response
    {
        $user = $this->getUser();
        if (!$group->getUsers()->contains($user)) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);


    }

    /**
     * @Route("/{slug}/edit", name="group_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Group $group): Response
    {
        $user = $this->getUser();

        if ($group->getCreator() === $this->getUser()) {
            $form = $this->createForm(GroupType::class, $group);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $group->setUpdatedAt(new DateTimeImmutable('now'));
                return $this->redirectToRoute('group_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('group/edit.html.twig', [
                'group' => $group,
                'form' => $form,
            ]);
        }
        throw new AccessDeniedException('Unable to access this page!');
    }

    /**
     * @Route("/{slug}", name="group_delete", methods={"POST"})
     */
    public function delete(Request $request, Group $group): Response
    {
        $user = $this->getUser();
        if (!$group->getCreator() === $user) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('group_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{slug}/add", name="group_add_user", methods={"GET","POST"})
     */
    public function addToGroup(Request $request, Group $group): Response
    {
        $user = $this->getUser();

        if ($group->getCreator() === $this->getUser()) {
            $form = $this->createForm(UserGroupType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email'=>$data]);
                $group->addUser($user);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('group_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('group/edit.html.twig', [
                'group' => $group,
                'form' => $form,
            ]);
        }
        throw new AccessDeniedException('Unable to access this page!');
    }
    /**
     * @Route("/{slug}/list", name="group_user_list", methods={"GET","POST"})
     */
    public function GroupList(Group $group): Response
    {
        if ($group->getCreator() === $this->getUser()) {
            $users = $group->getUsers();
            return $this->render('group/list.html.twig', [
                    'users' => $users,
                    'group'=> $group,
            ]);
        }
        throw new AccessDeniedException('Unable to access this page!');
    }
    /**
     * @Route("/{slug}/{email}/delete", name="group_user_delete", methods={"GET","POST"})
     */
    public function listDelete(Group $group,User $user): Response
    {
        if ($group->getCreator() === $this->getUser()) {

            if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->request->get('_token'))) {
                $group->removeUser($user);
            }

            return $this->redirectToRoute('group_index', [], Response::HTTP_SEE_OTHER);

        }
        throw new AccessDeniedException('Unable to access this page!');
    }


}
