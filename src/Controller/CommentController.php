<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\CommentType;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/{id}', name: 'delete', methods: ['GET'])]
    public function deleteComment(Comment $comment, EntityManagerInterface $entityManager) {
        $user = $this->getUser();
        $episode = $comment->getEpisode();

        if ($user->hasRole('ROLE_ADMIN') || ($user->hasRole('ROLE_CONTRIBUTOR') && $user == $comment->getAuthor())){
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('danger', 'The comment has been deleted');
        } else {
            $this->addFlash('danger', 'You are not allowed to delete this comment');
        } 
    
        return $this->redirectToRoute('public_episode_show', [
            'episodeSlug' => $episode->getSlug(),
        ], Response::HTTP_SEE_OTHER);
    }
}
