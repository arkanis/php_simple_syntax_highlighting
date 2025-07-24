Simple Syntax Highlighting for PHP
==================================

A simple to-the-point syntax highlighting function written in PHP. About 40 lines of PHP and usually one 10-20 line regex for each supported language.

~~~ php
require_once("simple_syntax_highlighting.php");

$html = simple_syntax_highlighting("return vec4(pos.xyz, 1);", "glsl");
// → <span class="landmark_b">return</span> <span class="landmark_c">vec4</span>(pos.<span class="variation_b">xyz</span>, <span class="variation_a">1</span>);
~~~
~~~ html
<style>
  /* Colors based on base16.dark (https://github.com/mzlogin/rouge-themes/tree/master?tab=readme-ov-file#base16dark) and base16-edge-dark (https://highlightjs.org/demo) */
  code[class^=lang_] {
    background-color: #151515; color: #d0d0d0; tab-size: 4;
    & span.landmark_a  { color: #d28445; }
    & span.landmark_b  { color: #d390e7; }
    & span.landmark_c  { color: #dbb774; }
    & span.variation_a { color: #5ebaa5; }
    & span.variation_b { color: #90a959; }
    & span.variation_c { color: #73b3e7; }
    & span.backdrop_a  { color: #848484; }
  }
</style>

<pre><code class=lang_glsl><?= $html ?></code></pre>
~~~

- It's just one function: `simple_syntax_highlighting($code, $language_name)`.
- The PHP code is tiny and this makes it easy to check. The rest are regex language definitions, but they can't hijack your system.
- The language definitions are inside `simple_syntax_highlighting()` for ease of use. But you can change that however you want. It's just an language_name => regex array.
- The styling is done via CSS. Names like "keyword" or "control-flow" get awkward for HTML, CSS, etc. Instead I went with "landmarks", "variations" and "backdrops".
  Clusters of landmarks should catch your eye (e.g. class definitions) while variation colors should just create navigatable color patterns. And backdrop puts something in the background (e.g. comments).


## The core ideas

1. Each language is one regular expression. Don't panic, extended features make those a lot easier to work with in PHP than in e.g. JavaScript or Java.
2. Each named group that matches something is translated into a `span` element: `(?<keyword> … )` → `<span class=keyword>…</span>`.
3. A group name like "lang_xyz" recursively highlights matched text in language xyz.

Details:

- Group names starting with `_` are ignored. You can use those how ever you want, e.g. for recursive patterns or back references.
- Numbers are stripped from the end of group names, e.g. "keyword1" and "keyword27" both result in `<span class=keyword>…</span>`.
  This is useful if you want to match constructs that have e.g. multiple keywords with other stuff inbetween.
- "lang_xyz" group names allow for nested languages like JS, CSS or PHP in HTML.
  They can also be used to split one language into mutliple regular expressions to simplify stuff (I used that for HTML attributes).


## Adding support for your own language

Let's say we want to add support for an INI-like language:

~~~
[section name]
name=value
~~~

`simple_syntax_highlighting` uses PHPs regular expressions for language definitions.
Regular expressions got a bad reputation, and while they're not simple, they're also not that complex. And for the complexity they put on the table they're extraordinarily useful. Spending a week to learn them over 20 years ago was one of the best spend weeks of my life.

Anyway, this isn't a regex tutorial. If you don't know the basics take a quick look at a tutorial.
Instead I'll explain the PHP and regex features that I use to make it simpler to work with them.

1. Add an empty regex to `$language_definitions` for the `ini` language:
   
   ~~~php
   …
   "ini" => <<<'EOD'
       (
       )x
   EOD,
   …
   ~~~
   
   - This uses PHPs [nowdoc string syntax](https://php.net/nowdoc). That way we don't have to worry about escaping quotes or `$` signs.
   - We use `()` as regex delimiters (PHP allows [several](https://php.net/regexp.reference.delimiters)). That way we don't have to escape `/` characters.
     Together with nowdow we don't have to worry about double escaping or escaping anything except special regex characters themselves.
   - The [`x` extended syntax modifier](https://php.net/reference.pcre.pattern.modifiers) of the regex ignores whitespaces and allows `# comments`.
     With it we can structure our regex into multiple lines, indent code, add comments, etc.
     In short, we don't end up with a single line thats an unreadable mess.
   
2. Add named groups to match the parts you want to highlight:
   
   ~~~php
   …
   "ini" => <<<'EOD'
       (
           ^ (?<name> \w+ ) = (?<value> .+ )
       |   ^ (?<section> \[ [^]]* \] )
       )xm
   EOD,
   …
   ~~~
   Output:
   ~~~html
   <span class=section>[section name]</span>
   <span class=name>name</span>=<span class=value>value</span>
   ~~~
   
   Personally I often put the regex and some test code into [regex101.com](https://regex101.com/) and go from there (you have to add the `g` modifier to get more than the first match).
   But you can use whatever you want to write your regex. I can recommend PHPs [Pattern Syntax documentation](https://php.net/reference.pcre.pattern.syntax).
   It's quite astonishing what you can do with PCRE regular expressions (e.g. [recursive patterns](https://php.net/regexp.reference.recursive)).
   
3. Change the named groups to the CSS class names you want to use on your page:
   
   ~~~php
   …
   "ini" => <<<'EOD'
       (
           # name=value line
           ^ (?<variation_a> \w+ ) = (?<variation_b> .+ )
       |   # [section] line
           ^ (?<landmark_a> \[ [^]]* \] )
       )xm
   EOD,
   …
   ~~~
   Output:
   ~~~html
   <span class=landmark_a>[section name]</span>
   <span class=variation_a>name</span>=<span class=variation_b>value</span>
   ~~~
   
   I use the landmark, variation and backdrop CSS classes in this project. But you can use whatever you want as long as it matches your CSS styles.
   I usually also add comments to the regexp at that point because the group names no longer give hints.
   
   Note: If you want to use one group name multiple times you can just append a number to it, e.g. `variation_a1`, `variation_a2` and so on.
   The `simple_syntax_highlighting()` function maps all of them to `variation_a`.


## Tips and tricks

- [Negative lookbehind assertions](https://php.net/regexp.reference.assertions) to match strings with escape sequences: `" [\s\S]*? (?<!\\) "`.
  
  This will match a `"` followed by any character including line breaks `[\s\S]` as few times as possible ([lazy](https://php.net/regexp.reference.repetition)) `*?` until the closing `"`.
  But the closing double quote must not be preceded by a `\`, thats what the negative lookbehind assertion `(?<! \\ )` does.
  
- [Recursive patterns](https://php.net/regexp.reference.recursive) to match complex stuff with matching brackets. But I only used that for Rubys [percent literals](https://ruby-doc.org/3.4.1/syntax/literals_rdoc.html#label-Percent+Literals) so far.
  
- Finding the endpoint of nested languages can be a bit tricky. Simplified JS in HTML starts with `<script>` and ends with `</script>`.
  But there is quite a bit of JS code out there that contains something like `output += "</script>"`.
  
  In that case I found it helpful to match JS strings while searching the closing `</script>`. That way `"</script>"` is consumed as a string and not matched as `</script>`.
  Here is an example similar to the language definition for HTML:
  
  ~~~
  (?<landmark_c01> <script> )
  (?<lang_js>      [\s\S]*? (?: (?: " [^"]*? " | ' [^']*? ' | ` [^`]*? ` ) [\s\S]*? )* )
  (?<landmark_c02> </script> )
  ~~~


## Goals and non-goals   

Goals:

- Reasonable, not necessarily correct, syntax highlighting for blogs, etc.
- Keep the syntax highlighting logic simple and fast
- Use extended regexp features of PHP to make language definitions relatively simple and compact. Most languages are just one regexp with 10-20 lines.

Non-goals:

- Correctness. This isn't meant for live code editors where syntax highlighting provides direct feedback. Instead it just has to look plausible and pretty. Use that to simplify things.
- Feature-completeness. There are plenty feature-complete and complex highlighting libraries out there (e.g. scrivo/highlight.php).
  The world doesn't need another one of those. Instead we take a way simpler approach and see how far it can take us (turns out quite far).
