<?php
/*
Plugin Name: Clean SEO Slugs
Plugin URI: http://www.xblurb.com/clean-seo-slugs-wordpress-plugin
Description: Remove MS Word Smart Quotes too and make SEO friendly Slugs.
Version: 0.2
Author: Muhammad Alyas
Author URI: http://www.xblurb.com/clean-seo-slugs-wordpress-plugin
*/

/*
Copyright Muhammad Alyas 2010

Licensed under the terms of the GPL version 2, see:
http://www.gnu.org/licenses/gpl.txt

Provided without warranty, inluding any implied warrant of merchantability or fitness for purpose.
*/

add_filter('name_save_pre', 'clean_seo_slugs', 0);

function clean_seo_slugs($slug) {
    // We don't want to change an existing slug
	if ($slug) return $slug;

	global $wpdb;
	$clean_slug = strtolower(stripslashes($_POST['post_title']));
	
$bad = array( 'Š','Ž','š','ž','Ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ',
'Þ','þ','Ð','ð','ß','Œ','œ','Æ','æ','µ','"',"'",'“','”',"\n","\r",'_',"‘","’");

$good = array( 'S','Z','s','z','Y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y',
'TH','th','DH','dh','ss','OE','oe','AE','ae','u','','','','','','','-','','');

// replace strange characters with alphanumeric equivalents
$clean_slug = str_replace($bad, $good, $clean_slug);
$clean_slug = trim($clean_slug);

// remove any duplicate whitespace, and ensure all characters are alphanumeric
$bad_reg = array('/\s+/','/[^A-Za-z0-9\-]/');
$good_reg = array('-','');
$clean_slug = preg_replace($bad_reg, $good_reg, $clean_slug);



$clean_seo_slug = preg_replace('/&.+?;/', '', $clean_slug); // kill HTML entities
// Turn it to an array and strip common words by comparing against c.w. array
$clean_seo_slug_array = array_diff (split("-", $clean_seo_slug), clean_seo_slugs_stop_words());
    // Turn the sanitized array into a string
$clean_seo_slug = join("-", $clean_seo_slug_array);

return $clean_seo_slug;
	
}

function clean_seo_slugs_stop_words () {
    return array ("&", "a", "adj", "ago", "ain't", "also", "am", "an", "and", "any", "are", "aren't", "as", "a's", "at", "b", "back", "been", "but", "by", "c", "can", "cannot", "cant", "can't", "caption", "cause", "causes", "certain", "certainly", "changes", "clearly", "c'mon", "co", "co.", "com", "come", "c's", "d", "daren't", "did", "didn't", "do", "does", "doesn't", "done", "don't", "e", "each", "edu", "eg", "else", "end", "et", "etc", "even", "ever", "ex", "f", "far", "few", "for", "from", "g", "get", "gets", "go", "goes", "got", "h", "had", "hadn't", "has", "hasn't", "have", "haven't", "he", "he'd", "he'll", "hello", "help", "here", "hereafter", "hereby", "herein", "here's", "hereupon", "hers", "he's", "hi", "him", "himself", "his", "hither", "how", "howbeit", "however", "i", "i'd", "ie", "if", "i'll", "i'm", "in", "inc", "inc.", "into", "is", "isn't", "it", "it'd", "it'll", "its", "it's", "itself", "i've", "j", "just", "k", "know", "known", "knows", "l", "latter", "latterly", "least", "less", "lest", "let", "let's", "likely", "likewise", "m", "mainly", "may", "maybe", "mayn't", "me", "mean", "meantime", "meanwhile", "merely", "might", "mightn't", "more", "moreover", "most", "mostly", "mr", "mrs", "much", "must", "mustn't", "my", "myself", "n", "name", "namely", "nd", "near", "nearly", "needn't", "needs", "neither","neverf", "neverless", "nevertheless",  "no", "nobody", "non", "none", "nonetheless", "noone", "no-one", "nor", "not", "nothing", "notwithstanding", "now", "nowhere", "o", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once","ones", "one's", "onto", "or", "otherwise", "ought", "oughtn't", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "p", "particular", "particularly", "past", "per", "perhaps", "placed", "presumably", "probably", "provided", "provides", "q", "que", "qv", "r", "rather", "rd", "re", "reasonably", "right", "s", "said", "saw", "say", "saying", "says", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "shall", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "since", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "specified", "specify", "specifying", "still", "sub", "such", "sup", "t", "take", "taken", "taking", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that'll", "thats", "that's", "that've", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "there'd", "therefore", "therein", "there'll", "there're", "theres", "there's", "thereupon", "there've", "these", "they", "they'd", "they'll", "they're", "they've", "thing", "things", "think", "this", "thorough", "thoroughly", "those", "though",  "through", "throughout", "thru", "thus", "till", "to", "too", "took", "toward", "towards", "tried", "tries", "truly", "t's", "twice", "two", "u", "un", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "uses", "using", "usually", "v", "various", "via", "viz", "vs", "w", "want", "wants", "was", "wasn't", "way", "we", "we'd", "welcome", "well", "we'll", "went", "were", "we're", "weren't", "we've", "what", "whatever", "what'll", "what's", "what've", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "where's", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who'd", "whoever", "whole", "who'll", "whom", "whomever", "who's", "whose", "why", "willing", "with", "within", "without", "wonder", "won't", "would", "wouldn't", "x", "y", "yes", "yet", "you", "you'd", "you'll", "your", "you're", "yours", "yourself", "yourselves", "you've", "z", "zero");
}
?>
