<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'subject',
        'content',
    ];

    /**
     * Get or initialize a template with self-healing defaults.
     */
    public static function getTemplate(string $key, array $defaults = []): self
    {
        return self::firstOrCreate(
            ['key' => $key],
            [
                'subject' => $defaults['subject'] ?? null,
                'content' => $defaults['content'] ?? '',
            ]
        );
    }

    /**
     * Replace placeholders in the template content with actual values.
     */
    public function format(array $variables): string
    {
        $text = $this->content;
        foreach ($variables as $key => $value) {
            $text = str_replace("%{$key}%", $value ?? '', $text);
        }
        return $text;
    }

    /**
     * Replace placeholders in the template subject with actual values.
     */
    public function formatSubject(array $variables): ?string
    {
        if (!$this->subject) {
            return null;
        }
        $text = $this->subject;
        foreach ($variables as $key => $value) {
            $text = str_replace("%{$key}%", $value ?? '', $text);
        }
        return $text;
    }
}
