<?php

function simple_syntax_highlighting($code, $language_name, $language_definitions, $span_attribute_name = "class") {
	if ( !isset($language_definitions[$language_name]) )
		return htmlspecialchars($code);
	$regexp = $language_definitions[$language_name];
	
	preg_match_all($regexp, $code, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL);
	
	$result = "";
	$pos = 0;
	foreach ($matches as $match_index => $match) {
		foreach ($match as $group_name => [$group_text, $group_start_offset]) {
			// Ignore numbered groups, groups starting with "_" or groups that match nothing.
			if ( is_int($group_name) or $group_name[0] == "_" or $group_text === null )
				continue;
			
			$text_before_group = substr($code, $pos, $group_start_offset - $pos);
			$result .= htmlspecialchars($text_before_group);
			
			// Remove numbers from the end of group names, so we can in effect use the same group name multiple times in
			// one match, e.g. to highlight keywords at the start and end.
			$group_name = rtrim($group_name, "0123456789");
			
			// This comparison is roughly 20% faster than str_starts_with() on PHP 8.1.2-1ubuntu2.21 (cli)
			if ( $group_name[0] == "l" and $group_name[1] == "a" and $group_name[2] == "n" and $group_name[3] == "g" and $group_name[4] == "_" ) {
				// If a group is something like "lang_css" process the group text recursively. That way we can nest languages as long as the
				// end of the nested region is known before the recursion.
				$nested_language = substr($group_name, 5);
				$result .= "<span $span_attribute_name=$group_name>" . simple_syntax_highlighting($group_text, $nested_language, $language_definitions) . "</span>";
			} else {
				// Start a span for every normal named group in a match. The end of the span is inserted by the $end_offsets logic above and
				// below. That way we get nested spans for nested named groups in the regexp.
				$result .= "<span $span_attribute_name=$group_name>" . htmlspecialchars($group_text) . "</span>";
			}
			
			$pos = $group_start_offset + strlen($group_text);
		}
	}
	
	// Add the code between the end of the last named group and the end of string
	$result .= htmlspecialchars(substr($code, $pos));
	
	return $result;
}