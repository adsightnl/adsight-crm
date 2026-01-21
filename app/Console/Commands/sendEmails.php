<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
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
        $leads = Leads::whereNull('email_send')->whereNotNull('email')->get();
        foreach ($leads as $lead) {
            dd($lead);
            //fetch template
            $template = Templates::find($lead->template_id);
            if ($template) {
                //remove http(s):// prefix from website
                $website = preg_replace('#^https?://#', '', $lead->website);
                $website = rtrim($website, '/');
                $website = str_replace('www.', '', $website);

                if($template -> template == 'adsightwebsite'){
                    //send email
                    Mail::to($lead->email)->send(new adsightwebsite(
                        $lead->email,
                        $website
                    ));
                }
                $lead->email_send = now();
                $lead->status = 'contacted';
                $lead->save();
            }
        }
    }
}
