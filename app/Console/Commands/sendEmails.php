<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Leads;
use App\Models\Templates;
use App\Mail\adsightwebsite;
use App\Mail\adsightcompany;


class sendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to leads based on their templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //fetch leads without sent email
        $leads = Leads::whereNull('email_send')->get();
        foreach ($leads as $lead) {
            //fetch template
            $template = Templates::find($lead->template_id);
            if ($template) {
                if($template -> template == 'adsightwebsite'){
                    //send email
                    \Mail::to($lead->email)->send(new adsightwebsite(
                        $lead->email,
                        $lead->website
                    ));
                }elseif($template -> template == 'adsightcompany'){
                    //send email
                    \Mail::to($lead->email)->send(new adsightcompany(
                        $lead->email,
                        $lead->website
                    ));
                }
                $lead->email_send = now();
                $lead->save();
            }
        }
    }
}
