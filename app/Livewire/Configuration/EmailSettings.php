<?php

namespace App\Livewire\Configuration;

use App\Models\NotificationTemplate;
use Livewire\Component;
use Livewire\Attributes\Layout;

class EmailSettings extends Component
{
    public $subject;
    public $content;
    public $successMessage = '';

    public function mount()
    {
        $template = NotificationTemplate::getTemplate('email', [
            'subject' => 'Statify Alert: %service% is %status%',
            'content' => $this->getDefaultEmailContent(),
        ]);
        $this->subject = $template->subject;
        $this->content = $template->content;
    }

    public function save($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;

        $this->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $template = NotificationTemplate::getTemplate('email');
        $template->update([
            'subject' => $this->subject,
            'content' => $this->content,
        ]);

        $this->successMessage = 'Email template updated successfully!';
        $this->dispatch('saved');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.configuration.email-settings');
    }

    /**
     * The default beautiful HTML email template.
     */
    protected function getDefaultEmailContent(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statify Alert</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Statify Monitoring</h1>
                            <p style="margin: 5px 0 0 0; color: #e0e7ff; font-size: 14px;">Service Status Change Notification</p>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <span style="display: inline-block; padding: 8px 16px; border-radius: 9999px; font-size: 14px; font-weight: 700; text-transform: uppercase; background-color: #fee2e2; color: #dc2626;">
                                    Status: %status%
                                </span>
                            </div>
                            <p style="margin: 0 0 20px 0; color: #374151; font-size: 16px; line-height: 1.5;">
                                Hello, a monitored service has changed its operational status. Details of the event are listed below:
                            </p>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #6b7280; font-weight: 600;" width="120">Service:</td>
                                    <td style="padding: 6px 0; font-size: 14px; color: #111827; font-weight: 700;">%service%</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #6b7280; font-weight: 600;">Server:</td>
                                    <td style="padding: 6px 0; font-size: 14px; color: #111827;">%server% (%server_ip%)</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #6b7280; font-weight: 600;">New Status:</td>
                                    <td style="padding: 6px 0; font-size: 14px; color: #dc2626; font-weight: 700; text-transform: uppercase;">%status%</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #6b7280; font-weight: 600;">Old Status:</td>
                                    <td style="padding: 6px 0; font-size: 14px; color: #4b5563; text-transform: uppercase;">%old_status%</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #6b7280; font-weight: 600;">Time:</td>
                                    <td style="padding: 6px 0; font-size: 14px; color: #111827;">%date%</td>
                                </tr>
                            </table>
                            <div style="text-align: center; margin-bottom: 10px;">
                                <a href="https://service.rcproject.it/statify/dashboard" style="display: inline-block; background-color: #4f46e5; color: #ffffff; padding: 12px 30px; font-size: 16px; font-weight: 600; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">
                                    Go to Dashboard
                                </a>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af;">
                            This is an automated alert from your Statify installation. Please do not reply to this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }
}
