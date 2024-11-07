<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MissionAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $poseur;
    public $client;
    public $date_pose;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($poseur, $client, $date_pose)
    {
        $this->poseur = $poseur;
        $this->client = $client;
        $this->date_pose = $date_pose;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nouvelle mission de pose assignée')
                    ->view('emails.mission_assigned');
    }
}
