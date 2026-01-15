<?php

$text = 'test <mark class="highlight">علم</mark> test';
preg_match_all('/<mark[^>]*>([^<]+)<\/mark>/u', $text, $matches);
echo "Text: $text\n";
echo "Matches:\n";
print_r($matches);
echo "\nExtracted terms:\n";
print_r($matches[1] ?? []);
