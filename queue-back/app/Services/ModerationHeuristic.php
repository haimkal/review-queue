<?php

namespace App\Services;

class ModerationHeuristic
{
    public function evaluate(string $title, string $content): array
    {
        $text = strtolower($title . ' ' . $content);

        $score = 0;
        $flags = [];

        if (preg_match('/https?:\/\/\S+/i', $text)) {
            $score += 20;
            $flags[] = 'contains_link';
        }

        $spamKeywords = ['free money', 'bitcoin', 'loan', 'casino', 'win big'];
        foreach ($spamKeywords as $kw) {
            if (str_contains($text, $kw)) {
                $score += 40;
                $flags[] = 'spam_keyword';
                break;
            }
        }

        if (preg_match('/!{3,}/', $text)) {
            $score += 50;
            $flags[] = 'excessive_punctuation';
        }

        if (mb_strlen(trim($content)) < 20) {
            $score += 10;
            $flags[] = 'too_short';
        }

        $score = max(0, min(100, $score));

        $suggestedAction = $score >= 60 ? 'reject' : 'approve';

        return [
            'risk_score' => $score,
            'flags' => array_values(array_unique($flags)),
            'suggested_action' => $suggestedAction,
        ];
    }
}
