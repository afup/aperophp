<?php

namespace Aperophp\Lib;

use Aperophp\Repository;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Output\OutputInterface;
use TijsVerkoyen\Akismet\Akismet;

class Antispam
{
    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var Repository\User
     */
    protected $users;

    /**
     * @var Repository\Member
     */
    protected $members;

    /**
     * @var Repository\DrinkParticipant
     */
    protected $participants;

    /**
     * @var Repository\DrinkComment
     */
    protected $comments;

    /**
     * @var Akismet
     */
    protected $akismet;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Antispam constructor.
     *
     * @param Connection $conn
     * @param Repository\User $users
     * @param Repository\Member $members
     * @param Repository\DrinkParticipant $participants
     * @param Repository\DrinkComment $comments
     * @param Akismet $akismet
     */
    public function __construct(
        Connection $conn,
        Repository\User $users,
        Repository\Member $members,
        Repository\DrinkParticipant $participants,
        Repository\DrinkComment $comments,
        Akismet $akismet
    )
    {
        $this->conn = $conn;
        $this->users = $users;
        $this->members = $members;
        $this->participants = $participants;
        $this->comments = $comments;
        $this->akismet = $akismet;
    }

    /**
     * Set output
     *
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Send a message to the output
     *
     * @param $msg
     */
    protected function output($msg)
    {
        if(!is_null($this->output)) {
            $this->output->writeln($msg);
        }
    }

    /**
     * Dedupe users with same email
     *
     * For each duplicated user, check if there is at least one used occurrence
     * If yes, aggregate all users on one occurence
     * If no, delete user
     */
    public function dedupeUsers()
    {
        $this->output("<info>Dedupe duplicated emails</info>");

        $duplicatedUsers = $this->users->getDuplicatedEmails();
        $nbDuplicatedUsers = count($duplicatedUsers);
        $this->output(sprintf("%s duplicated emails", $nbDuplicatedUsers));

        foreach ($duplicatedUsers as $i => $email) {
            $this->output->write(sprintf('[%6d/%d]', $i, $nbDuplicatedUsers));

            // Get all users with this email
            $users = $this->users->findByEmail($email['email']);

            /**
             * TODO
             *
             * Récupérer les membres valides associés à cet utilisateur
             * Si au moins un membre
             *      Tout associer au premier member
             * Sinon
             *      Tout associer au premier user
             * FinSi
             *
             * Supprimer les autre users
             */

            // Get user associated to member
            $members = array_filter($users, function($user) {
                return !is_null($user['member_id']);
            });

            // Si au moins un utilisateur est associé à un membre, on prend le premier, sinon on prend le premier user
            $userToKeep = (count($members) > 0) ? reset($members) : reset($users);

            // Associate all comments from users from an email to the valid user
            // Associate all participations from users from an email to the valid user
            // Delete users from an email but the valid user

            $this->output->writeln('');

            /*
             * # Est-ce que l'utilisateur est associé à un membre ?
            $validUsers = array_filter($users, function($user) {
                return !is_null($user['member_id']);
            });

            $participations = 0;
            $comments = 0;
            foreach($users as $user) {
                $participations += $this->participants->countByUserId($user['id']);
                $comments += $this->comments->countByUserId($user['id']);
            }

            // No valid users, no participations, no comments => accounts to remove
            if (count($validUsers) == 0 && $participations == 0 && $comments == 0) {
                $this->output->writeln(sprintf(
                    '[%5d/%5d]<error>[%s]</error>',
                    $i, $nbDuplicatedUsers,
                    $email['email']
                ));

                // Remove each users
            } else {
                $this->output->writeln(sprintf(
                    '[%5d/%5d]<info>[users:%3d][valid:%3d][comments:%3d][participations:%3d][%s]</info>',
                    $i, $nbDuplicatedUsers,
                    count($users),
                    count($validUsers),
                    $comments,
                    $participations,
                    $email['email']
                ));

                // Agregate all datas on one user
            }

            if(count($validUsers) > 1) {
                $this->output->writeln('  => VALID');
            }
            // */
        }
    }

    public function removeSpamAccount()
    {
        // Trouver les users qui n'ont aucun commentaire non-spam, aucune participation et les supprimer
    }

    /**
     * Check existing comments to
     *
     * For each existing comment, check if it's a spam or not
     */
    public function checkComments()
    {
        $this->output("<info>Check existing comment to find spams</info>");

        $commentsToCheck = $this->comments->getToCheckSpam();
        $spams = 0;
        $this->output(sprintf('%s comments to check', count($commentsToCheck)));

        foreach($commentsToCheck as $i => $comment) {
            if(($i+1)%10 == 0) { $this->output(sprintf('%s/%s', $i+1, count($commentsToCheck))); }

            try {
                $isSpam = $this->akismet->isSpam(
                    $comment['content'],
                    $comment['firstname'] .' '. $comment['lastname'],
                    $comment['email'],
                    null,
                    null,
                    'comment'
                );

                if($isSpam) {
                    $spams++;
                    $this->comments->markAsSpam($comment['comment_id']);
                    $this->output(sprintf("\t<info>Comment #%s flaged as spam</info>", $comment['comment_id']));
                }
            }
            catch(\Exception $e)
            {
                $this->output(sprintf('<error>Something went wrong ! (%s)</error>', $e->getMessage()));
                return false;
            }
        }

        $this->output(sprintf('Finished ! %s comments flaged as spam on %s comment checked', $spams, count($commentsToCheck)));

        return true;
    }

    /**
     * Remove comment marked as spam
     */
    public function removeSpamComments()
    {
        die("Est-ce qu'il ne vaudrait pas mieux supprimer les comptes en même temps ?");
        $this->output("<info>Remove comments marked as spam</info>");
        $nbDeletion = $this->comments->removeSpam();

        $this->output(sprintf("%s comments removed", $nbDeletion));
    }
}
