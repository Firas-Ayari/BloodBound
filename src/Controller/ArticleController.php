<?php

namespace App\Controller;

use DATETIME;
use Bleach\Bleach;
use App\Entity\vote;
use App\Entity\Rating;
use App\Form\VoteType;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Mime\Email;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
class ArticleController extends AbstractController
{
   
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('backoffice/article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_article_index_Front', methods: ['GET'])]
    public function indexFront(ArticleRepository $articleRepository): Response
    {
        return $this->render('frontoffice/article/indexFront.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index_Front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
    #[Route('/new/front', name: 'app_article_new_front', methods: ['GET', 'POST'])]
    public function newFront(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index_Front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/article/ajoutArticleFront.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('backoffice/article/show.html.twig', [
            'article' => $article,
        ]);
    }
    #[Route('/{id}/show', name: 'app_article_showfront', methods: ['GET', 'POST'])]
    public function showfront(Article $article,Request $request,EntityManagerInterface $em , ArticleRepository $articleRepo , MailerInterface $mailer): Response
    {
        
        //creer commentaire
        $comment = new Comment();
        //generer formulaire 
        $commentForm =$this->createForm(CommentType::class,$comment);

        $commentForm->handleRequest($request);
        //traitement du formulaire
        if($commentForm->isSubmitted()&& $commentForm->isValid())
        {

            $email = (new TemplatedEmail())
            ->from('ameni@exemple.com')
            ->to('ameni.drira@esprit.tn')
            ->subject('Test email')
            ->htmlTemplate('comment/testmail.html.twig');
            
            $mailer->send($email);
            $comment->setArticle($article);
            //on recupere le contenu du champ parentid 
            $parent = $commentForm->get("parent")->getData();
            //chercher commentaire correspondant
           
            $em=$this->getDoctrine()->getManager();//tejbed entity manager bech testaaml repo
            /*if($parent != null)
            {
            $parent= $em->getRepository(Comment::class)->find($parentid);
            }*/
            //on definit le parent
            $comment->setParent($parent ?? null);

            $em->persist($comment);


           // Récupérer le contenu du commentaire
           $dirty_comment = $comment->getContent();

           // Définir la liste des gros mots à filtrer
           $bad_words = array('merde', 'putain');

           // Utiliser une expression régulière pour remplacer les gros mots par des étoiles
           $clean_comment = preg_replace('/('.implode('|', $bad_words).')/i', '***', $dirty_comment);


           // Enregistrer le nouveau contenu du commentaire dans la base de données
           $comment->setContent($clean_comment);

            $em->flush();
            $this->addFlash('message','votre commentaire est envoyée');
            return $this->redirectToroute('app_article_showfront',['id'=>$article->getId()]);
            }

        return $this->render('frontoffice/article/showfront.html.twig', [
            'article' => $article,
            'commentForm'=>$commentForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt($article->getCreatedAt());
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editfront', name: 'app_article_editfront', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt($article->getCreatedAt());
            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index_Front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontoffice/article/editfront.html.twig', [
            'article' => $article,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods:['POST' ])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete', name: 'app_article_deletefront', methods: [ 'POST'])]
    public function deletefront(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index_Front', [], Response::HTTP_SEE_OTHER);
    }
    /*#[Route('/{id}/vote/{value}', name:"vote")]
    public function vote(Article $article, int $value): Response
    {
        // Check if the user has already voted on this article
        $vote = $this->getDoctrine()
            ->getRepository(Vote::class)
            ->findOneBy([
                'article' => $article,
                'user' => $this->getUser(),
            ]);

        if ($vote) {
            // User has already voted, update the value of their vote
            $vote->setValue($value);
        } else {
            // User hasn't voted yet, create a new vote
            $vote = new Vote();
            $vote->setArticle($article)
                ->setUser($this->getUser())
                ->setValue($value);
        }

        // Save the vote to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($vote);
        $entityManager->flush();

        return $this->redirectToRoute('app_article_showfront', ['id' => $article->getId()]);
    }*/
    
    
}
