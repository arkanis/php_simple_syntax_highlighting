<?php

return [
	// Java SE 24 Language Specification: https://docs.oracle.com/javase/specs/jls/se24/html/index.html
	// Pretty much everything from Chapter 3. Lexical Structure: https://docs.oracle.com/javase/specs/jls/se24/html/jls-3.html
	"java" => <<<'EOD'
		(
		    # Most keywords and important operators
		    (?<landmark_a>
		        \b (?: abstract | new | assert | package | synchronized | private | this | implements | protected | import | public | throws | enum | instanceof | transient | extends | interface | static | void | class | volatile | native | super | _ | exports | opens | requires | uses | module | permits | sealed | non-sealed | provides | to | open | record | transitive | with ) \b
		    |   ->
		    )
		|   # Control-flow keywords
		    (?<landmark_b>   \b (?: continue | for | switch | default | if | do | break | throw | else | case | return | catch | try | final | finally | while | yield | when ) \b )
		|   # Types
		    (?<landmark_c>   \b (?: var | boolean | double | byte | int | short | char | long | float | String ) \b (?: \[\] )? )
		|   # Values, prefixed numbers, decimal numbers
		    (?<variation_a>
		        \b (?: null | true | false ) \b
		    |   \b (?i: 0x [0-9a-f_]+ l? | 0b [01_]+ l? ) \b
		    |   [-+]? \b (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? [lLfFdD]? \b
		    )
		|   # Text blocks, strings, character literals
		    (?<variation_b>  """ [\s\S]*? (?<!\\) """ | " .*? (?<!\\) " | ' .*? (?<!\\) ' )
		|   # Comments
		    (?<backdrop_a>   //.* | /\* [\s\S]+? \*/ )
		|   # Calls
		    (?<variation_c>  \b \w+ (?= \( ) )
		)x
	EOD,
	// Data types from https://www.postgresql.org/docs/17/datatype.html
	// Keywords based on highlight/pgsql.js keywords, added some here and there (e.g. key, left, right).
	// Value, string and comment based on https://www.postgresql.org/docs/17/sql-syntax-lexical.html
	"sql" => <<<'EOD'
		(
		    # Keywords
		    (?<landmark_a>  \b (?: abort | alter | analyze | begin | call | checkpoint | close | cluster | comment | commit | copy | create | deallocate | declare | delete | discard | do | drop | end | execute | explain | fetch | grant | import | insert | listen | load | lock | move | notify | prepare | reassign | refresh | reindex | release | reset | revoke | rollback | savepoint | security | select | set | show | start | truncate | unlisten | update | vacuum | values | aggregate | collation | conversion | database | default | privileges | domain | trigger | extension | foreign | wrapper | table | function | group | language | large | object | materialized | view | operator | class | family | policy | publication | role | rule | schema | sequence | server | statistics | subscription | system | tablespace | configuration | dictionary | parser | template | type | user | mapping | prepared | access | method | cast | as | transform | transaction | owned | to | into | session | authorization | index | procedure | assertion | all | analyse | and | any | array | asc | asymmetric | both | case | check | collate | column | concurrently | constraint | cross | deferrable | range | desc | distinct | else | except | for | freeze | from | full | having | ilike | in | initially | inner | intersect | is | isnull | join | lateral | leading | like | limit | natural | not | notnull | null | offset | on | only | or | order | outer | overlaps | placing | primary | references | returning | similar | some | symmetric | tablesample | then | trailing | union | unique | using | variadic | verbose | when | where | window | with | by | returns | inout | out | setof | if | strict | current | continue | owner | location | over | partition | within | between | escape | external | invoker | definer | work | rename | version | connection | connect | tables | temp | temporary | functions | sequences | types | schemas | option | cascade | restrict | add | admin | exists | valid | validate | enable | disable | replica | always | passing | columns | path | ref | value | overriding | immutable | stable | volatile | before | after | each | row | procedural | routine | no | handler | validator | options | storage | oids | without | inherit | depends | called | input | leakproof | cost | rows | nowait | search | until | encrypted | password | conflict | instead | inherits | characteristics | write | cursor | also | statement | share | exclusive | inline | isolation | repeatable | read | committed | serializable | uncommitted | local | global | sql | procedures | recursive | snapshot | rollup | cube | trusted | include | following | preceding | unbounded | range | groups | unencrypted | sysid | format | delimiter | header | quote | encoding | filter | off | force_quote | force_not_null | force_null | costs | buffers | timing | summary | disable_page_skipping | restart | cycle | generated | identity | deferred | immediate | level | logged | unlogged | of | nothing | none | exclude | attribute | usage | routines | true | false | nan | infinity; | alias | begin | constant | declare | end | exception | return | perform | raise | get | diagnostics | stacked | foreach | loop | elsif | exit | while | reverse | slice | debug | log | info | notice | warning | assert | open | key | left | right ) \b )
		|   # Types
		    (?<landmark_b>  \b (?: (?: small | big ) (?: int | serial ) | integer | int[248]? | serial[248]? | decimal | numeric | real | double precision | float[48]? | boolean | bool | bit | bit varying | varbit | character | char | character varying | varchar | date | time | timestamp | interval | json | jsonb | xml | numeric | decimal | text ) \b )
		|   # Prefixed numbers, decimal numbers
		    (?<variation_a>
		        \b (?i: 0x [0-9a-f_]+ | 0b [01_]+ | 0o [0-7_]+ ) \b
		    |   [-+]? \b (?: \d [\d_]* (?: \. [\d_]+ )? | \. [\d_]+ ) (?: [eE] [-+]? \d+ )?
		    )
		|   # Strings, dollar quoted strings (only match markers, content is probably code so leave it for highlighting later on)
		    (?<variation_b>  ' .*? ' | \$ [a-z0-9_]* \$ )
		|   # Positional parameter in function bodies
		    (?<landmark_c>   \$ \d+ )
		|   # Comments
		    (?<backdrop_a>   --.* | /\* [\s\S]+? \*/ )
		|   # Calls
		    (?<variation_c>  \b \w+ (?= \( ) )
		)xi
	EOD,
	// Everything from The OpenGL® Shading Language Version 4.60.8: https://registry.khronos.org/OpenGL/specs/gl/GLSLangSpec.4.60.pdf
	// Reusing the "string" class for vector swizzle patterns to make the code more interesting.
	"glsl" => <<<'EOD'
		(
		    # Most keywords, preprocessor directives
		    (?<landmark_a>
		        \b (?: layout | subroutine | const | in | out | inout | attribute | uniform | varying | buffer | shared | centroid | sample | patch | flat | smooth | noperspective | invariant | precise | coherent | volatile | restrict | readonly | writeonly | struct | lowp | mediump | highp | precision ) \b
		    |   \#\w+
		    )
		|   # Control-flow keywords
		    (?<landmark_b>  \b (?: break | continue | do | for | while | switch | case | default | if | else | discard | return ) \b )
		|   # Types
		    (?<landmark_c>  \b (?: 
		        int | uint | void | bool |  float | double | atomic_uint |
		        [ibud]?vec[234] | d?mat[234](?:x[234])? | [iu]? subpassInput (?: MS )? |
		        [iu]? (?: sampler | image | texture ) (?: 1D | 2DRect | 2DMS | 2D | 3D | Cube | Buffer )? (?:Array)? (?:Shadow)?
		    ) \b )
		|   # Values, prefixed numbers, floating point numbers
		    (?<variation_a>
		        \b (?: __LINE__ | __FILE__ | __VERSION__ | true | false  |  (?i: 0x [0-9a-f]+ u? | 0 [0-7]+ u? ) ) \b
		    |   [-+]? \b (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? (?i: f | lf )?
		    )
		|   # Vector swizzle patterns
		    (?<variation_b>  (?<= \. ) [xyzwrgbastpq]{1,4} )
		|   # Comments
		    (?<backdrop_a>   //.* | /\* [\s\S]+? \*/ )
		|   # Calls
		    (?<variation_c>  \b \w+ (?= \( ) )
		)x
	EOD,
	// Keywords from https://ruby-doc.org/3.4.1/syntax/keywords_rdoc.html
	// Can't differentiate easily between keywords and controll flow because "end" is used in both, structure and control flow.
	// Operators from https://ruby-doc.org/3.4.1/syntax/precedence_rdoc.html (but only added .. and ...).
	// Using "control" class for class and instance variables, 
	"ruby" => <<<'EOD'
		(
		    # Keywords and important operators
		    (?<landmark_a>  -> | \.\.\.? | \b \s* (?: __ENCODING__ | __LINE__ | __FILE__ | BEGIN | END | alias | and | begin | break | case | class | def | defined\? | do | else | elsif | end | ensure | false | for | if | in | module | next | nil | not | or | redo | rescue | retry | return | self | super | then | true | undef | unless | until | when | while | yield ) \b )
		|   # Class and instance variabls, hash keys, argument lists
		    (?<landmark_b>  @@? \w+ | \$\$? \w+ | \w+ : | \| [^|]+? \| )
		|   # Constants, class names
		    (?<landmark_c>  \b (?: [A-Z][A-Z0-9_]* | [A-Z]\w+ ) \b )
		|   # Values, prefixed and floating point numbers, symbols
		    (?<variation_a>
		        \b (?: nil | true | false ) \b
		    |   \b (?i: 0x [0-9a-z_]+ | 0b [01_]+ | 0o [0-7_]+ | 0d [0-9]+ | 0 [0-7]+ ) \b
		    |   [-+]? \b (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? [ri]{0,2}
		    |   : (?: \w+ | ' .*? (?<!\\) ' | " .*? (?<!\\) " )
		    )
		|   # Strings, heredoc, percent literals, regexp
		    (?<variation_b>
		        ' .*? (?<!\\) ' | " .*? (?<!\\) "
		    |   << [\-~]? [\`']? (?<_id>\w+) [\`']? [\s\S]*? \k{_id}
		    |   % [qQwWiIsrx]?
		        (?: (?<_qp> \( (?: (?>[^()]+)   | (?&_qp) )* \) )
		        |   (?<_qs> \[ (?: (?>[^\[\]]+) | (?&_qs) )* \] )
		        |   (?<_qc> \{ (?: (?>[^{}]+)   | (?&_qc) )* \} )
		        |   (?<_qa> <  (?: (?>[^<>]+)   | (?&_qa) )* >  )
		        )
		    |   / .*? (?<!\\) /
		    )
		|   # Comments
		    (?<backdrop_a>   \#.* | ^ =begin [\s\S]+? ^ =end )
		|   # Stuff that looks like method calls
		    (?<variation_c>
		        (?: ^ [ \t]* | (?<=\.) ) [\w?!]+ (?= \s+ [^!~+\-*/%<>&|^=.?:] )
		    |   \b [\w?!]+ (?= \( )
		    )
		)xm
	EOD,
	// Keywords from https://www.man7.org/linux/man-pages/man1/bash.1.html#RESERVED_WORDS
	"bash" => <<<'EOD'
		(
		    # Variable assignments and simple access
		    (?<landmark_b>   ^ \w+ = | \$\w+ )
		|   # Keywords, subshells and parenthesis
		    (?<landmark_a>   \b (?: ! | case |  coproc | do | done | elif | else | esac | fi | for | function | if | in | select | then | until | while | time ) \b  |  \[\[ | \]\] | \$\( | \( | \) )
		|   # Arguments like -x, -xaf or --arg-name
		    (?<landmark_c>   (?<=\s) (?: -\w+ | --[a-zA-Z0-9_-]+ (?= \s | $ | = ) ) )
		|   # Numbers
		    (?<variation_a>
		        \b (?i: 0x [0-9a-z_]+ | 0b [01_]+ | 0 [0-7]+ ) \b
		    |   [-+]? \b (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )?
		    )
		|   # Strings
		    (?<variation_b>  ' .*? (?<!\\) ' | " .*? (?<!\\) " )
		|   # Command (first word in a line)
		    (?<variation_c>  ^ [ \t]* [\w./]+ )
		|   # Comments
		    (?<backdrop_a>   \# .* )
		)xm
	EOD,
	// Mostly based on the C23 (ISO/IEC 9899:2024 (en) - N3220 working draft): https://www.open-std.org/jtc1/sc22/wg14/www/docs/n3096.pdf
	"c" => <<<'EOD'
		(
		    # Non-control-flow keywords, preprocessor directives
		    (?<landmark_a>
		        \b (?: alignas | alignof | constexpr | enum | extern | inline | register | restrict | signed | sizeof | static | static_assert | struct | thread_local | typedef | typeof | typeof_unqual | union | unsigned | volatile | _Atomic | _Generic | _Imaginary | _Noreturn ) \b
		    |   \#\w+
		    )
		|   # Control-flow keywords
		    (?<landmark_b> \b (?: if | else | for | do | while | break | continue | switch | case | default | return | goto ) \b )
		|   # Types and important operators
		    (?<landmark_c>
		        \b (?: auto | bool | char | const | double | float | int | long | short | void | _BitInt | _Complex | _Decimal(?:128|64|32) | \w+_t ) \b
		        (?: \* | \[\] | \[ \d+ \] )*
		    |   \& | \[ | \]
		    |   \* (?= \w | \( )
		    )
		|   # Values and numbers
		    (?<variation_a>
		        \b (?: __LINE__ | __FILE__ | TRUE | true | FALSE | false | NULL | nullptr | (?i: 0x [0-9a-f]+ | 0b [01] | 0 [0-7]+ ) [wbulWBUL]* )
		    |   [-+]? \b (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? [lfd]*
		    )
		|   # Strings
		    (?<variation_b>
		        (?: L | u8 | u | U )? (?: ' .*? (?<!\\) ' | " .*? (?<!\\) " )
		    |   (?<= \#include ) \s+ < [^>]+ >
		    )
		|   # Comments
		    (?<backdrop_a>   //.* | /\* [\s\S]+? \*/ )
		|   # Calls
		    (?<variation_c>  \b \w+ (?= \( ) )
		)x
	EOD,
	"html" => <<<'EOD'
		(
		    # Embedded JavaScript (catch strings in scripts in case they contain "</script>" and would wrongly end the script section)
		    (?<variation_c01> <script ) (?<lang_html_attr0> [^>]+ )? (?<variation_c02> > ) (?: .* \n )? (?<lang_js> [\s\S]*? (?: (?: " [^"]*? " | ' [^']*? ' | ` [^`]*? ` ) [\s\S]*? )* ) (?<variation_c03> </script> )
		|   # Embedded CSS
		    (?<variation_c11> <style ) (?<lang_html_attr1> [^>]+ )? (?<variation_c12> > ) (?<lang_css> [\s\S]*? ) (?<variation_c13> </style> )
		|   # Embedded PHP
		    (?<variation_c21> <\? (?: php | = )? ) (?<lang_php> [\s\S]*? (?: (?: " [^"]*? " | ' [^']*? ' | <<< '? (?<_id> \w+ ) '? \n [\s\S]+? \g{_id} ) [\s\S]*? )* ) (?<variation_c22> \?> )
		|   # HTML start tags
		    (?<variation_c31> < \w+ ) (?<lang_html_attr2> [\s\S]*? (?: (?: " [^"]*? " | ' [^']*? ' ) [\s\S]*? )* ) (?<variation_c32> > )
		|   # Doctype
		    (?<variation_c41> <! \w+ ) (?<variation_a> [^>]+ )? (?<variation_c42> > )
		|   # HTML end tags
		    (?<variation_c>  </ \w+ > )
		|   # Comments
		    (?<backdrop_a>  <!-- [\s\S]+? --> )
		)x
	EOD,
	"html_attr" => <<<'EOD'
		(
		    # Attribute name
		    (?<variation_a> [\w-]+ )
		    # Optionally followed by a value
		    (?: = (?<variation_b> " [^"]*? " | ' [^']*? ' | \S+ ) )?
		)x
	EOD,
	// Mostly based on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Lexical_grammar
	"js" => <<<'EOD'
		(
		    # Non-control-flow keywords and important operators
		    (?<landmark_a>
		        \b (?: import | as | from | export | function | async | class | extends | static | implements | interface | package | private | protected | public | get | set | this | super | new | delete | instanceof | typeof | enum | void ) \b
		    |   =>
		    )
		|   # Control-flow keywords
		    (?<landmark_b>  \b (?: if | else | for | in | of | do | while | continue | switch | case | default | break | try | catch | finally | throw | debugger | return | with | yield | await ) \b )
		|   # Variable definitions
		    (?<landmark_c>  \b (?: var | const | let ) \b )
		|   # Values, prefixed numbers, floating point numbers
		    (?<variation_a>
		        \b (?: true | false | null | undefined | arguments ) \b
		    |   \b (?i: 0x [0-9a-f_]+ | 0b [01_] | 0o? [0-7_]+ ) n? \b
		    |   [-+]? \b (?: \d [\d_]* (?: \. [\d_]+ )? | \. [\d_]+ ) (?: [eE] [-+]? [\d_]+ )? n?
		    )
		|   # Comments
		    (?<backdrop_a>   //.* | /\* [\s\S]+? \*/ )
		|   # Strings, regexp
		    (?<variation_b>  " [\s\S]*? (?<!\\) " | ' [\s\S]*? (?<!\\) ' | ` [\s\S]*? (?<!\\) ` | / [\s\S]+? (?<!\\) / [gmiyuvsd]* )
		|   # Calls
		    (?<variation_c>  \b \w+ (?= \( ) )
		)x
	EOD,
	// Mostly written from memory, with some inspiration from https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_selectors.
	"css" => <<<'EOD'
		(
		    # Important operators, :: to make pseudo-elements look distinct from pseudo-selectors.
		    (?<landmark_b> [&>+~] | :: )
		|   # Main selectors like elements, @page, …
		    (?<landmark_c> (?<= ^ | \s | [^\w] ) [\w@-]+ \b )
		|   # Pseudo-selectors like :nth-of-child, :not, :has, …
		    (?<variation_c>    : [\w-]+ )
		|   # Property list (recursive patter to get proper nesting of {})
		    (?<_p> { (?<lang_css_props> (?: (?>[^{}]+) | (?&_p) )* ) } )
		)x
	EOD,
	"css_props" => <<<'EOD'
		(
		    # Property value pair
		    (?<landmark_a> [\w-]+ ) : (?<variation_a> [^;}]* ) [;}]
		|   # Nested selector
		    (?<lang_css> \S[^{]+? (?<_p> { (?: (?>[^{}]+) | (?&_p) )* } ) )
		)xm
	EOD,
	// Keywords from https://www.php.net/manual/en/reserved.keywords.php
	// Types from https://www.php.net/manual/en/language.types.type-system.php
	// Heredoc strings from https://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
	"php" => <<<'EOD'
		(
		    # Non-control-flow keywords, important operators
		    (?<landmark_c>
		        \b (?<! \$ ) (?: function | use | fn | namespace | class | trait | interface | extends | implements | abstract | final | private | protected | public | readonly | and | or | xor | clone | new | unset | include | include_once | require | require_once | instanceof | insteadof | list | self | static | parent | __CLASS__ | __DIR__ | __FILE__ | __FUNCTION__ | __LINE__ | __METHOD__ | __PROPERTY__ | __NAMESPACE__ | __TRAIT__ ) \b
		    |   => | &
		    )
		|   # Control-flow keywords
		    (?<landmark_b>  \b (?<! \$ ) (?: if | else | elseif | endif | switch | case | break | default | endswitch | match | for | continue | endfor | foreach | as | endforeach | do | while | endwhile | try | catch | finally | throw | return | goto | die | yield from | yield | declare | enddeclare ) \b )
		|   # Types, named arguments
		    (?<landmark_a>  \b (?<! \$ ) (?: bool | int | float | string | array | object | never | void | callable | const | global | static ) \b | \w+: )
		|   # Values, prefixed numbers, floating point numbers
		    (?<variation_a>
		        \b (?<! \$ ) (?i: true | false | null ) \b
		    |   \b (?i: 0x [0-9a-f_]+ | 0b [01_] | 0o? [0-7_]+ ) \b
		    |   [-+]? \b (?: \d [\d_]* (?: \. [\d_]+ )? | \. [\d_]+ ) (?: [eE] [-+]? [\d_]+ )?
		    )
		|   # Strings, heredoc strings
		    (?<variation_b>  " [\s\S]*? (?<!\\) " | ' [\s\S]*? (?<!\\) ' | <<< '? (?<_id> \w+ ) '? \n [\s\S]+? \g{_id} )
		|   # Comments
		    (?<backdrop_a>   \#.* | //.* | /\* [\s\S]+? \*/ )
		|   # Calls
		    (?<variation_c>  \b \w+ (?= \( ) )
		)x
	EOD
];