<?php

$code_examples = [
	["java", <<<'EOD'
		// Language test
		// HTML escaping <b>test</b>.
		import com.sun.net.httpserver.*;
		import java.nio.file.*;
		/* multiline
		comment */
		import static java.nio.charset.StandardCharsets.UTF_8;
		/* multiline comment */
		
		+16 x -16 x
		3.141 x +3.141 x -3.141 x 
		3.234e17 x +3.234e17 x -3.234e17 - 15
		0xffaa_0011 x 0XFFAA_0011 x
		0b0011_0011 x 0B0011_0011 x
		x null 
		
		x 'test' x '' x 'a \' b' x
		x "test" x "" x "a \" b" x
		
		class Code {
			public static void main(String[] args) {
				String text = """
					The quick brown fox jumps over the lazy dog
				""";
				
				String code =
					"""
					String text = \"""
						The quick brown fox jumps over the lazy dog
					\""";
					""";
			}
		}
		
		class MiniHTTPServer {
		  public static void main(String[] args) throws java.io.IOException {
			var server = HttpServer.create(new java.net.InetSocketAddress("localhost", 8080), 0);
			
			server.createContext("/", con -> {
			  con.getResponseHeaders().set("Content-Type", "text/html; charset=UTF-8");
			  con.sendResponseHeaders(200, 0);
			  var out = con.getResponseBody();
			  out.write("Hello World!".getBytes(UTF_8));
			  out.close();
			});
			server.createContext("/file", con -> {
			  con.getResponseHeaders().set("Content-Type", "text/html; charset=UTF-8");
			  con.sendResponseHeaders(200, 0);
			  Files.copy(Path.of("some_file.html"), con.getResponseBody());
			  con.close();
			});
			
			server.start();
		  }
		}
		EOD
	],
	["java", <<<'EOD'
		public static void drawSphere(double R, double k, double ambient){
			double[] vec = new double[3];
			for(int i = (int)Math.floor(-R); i <= (int)Math.ceil(R); i++){
				double x = i + .5;
				for(int j = (int)Math.floor(-2 * R); j <= (int)Math.ceil(2 * R); j++){
					double y = j / 2. + .5;
					if(x * x + y * y <= R * R) {
						vec[0] = x;
						vec[1] = y;
						vec[2] = Math.sqrt(R * R - x * x - y * y);
						normalize(vec);
						double b = Math.pow(dot(light, vec), k) + ambient;
						int intensity = (b <= 0) ?
									shades.length - 2 :
									(int)Math.max((1 - b) * (shades.length - 1), 0);
						System.out.print(shades[intensity]);
					} else
						System.out.print(' ');
				}
				System.out.println();
			}
		}
		EOD
	],
	["java", <<<'EOD'
		// Run with: java -classpath sqlite-jdbc.jar:. Sqlite.java
		class Sqlite {
		  public static void main(String[] args) throws java.sql.SQLException {
			try (var con = java.sql.DriverManager.getConnection("jdbc:sqlite:data.db", null, null)) {
			  try (var stmt = con.prepareStatement("SELECT id, body FROM posts")) {
				var resultset = stmt.executeQuery();
				while( resultset.next() ) {
				  System.out.print(resultset.getInt("id") + ": ");
				  System.out.println(resultset.getString("body"));
				}
			  }
			}
		  }
		}
		EOD
	],
	["sql", <<<'EOD'
		CREATE TABLE posts (
			id       INTEGER PRIMARY KEY,
			topic_id INTEGER,
			body     TEXT
		);
		CREATE INDEX posts_topic_index ON posts (topic_id);
		
		-- https://www.postgresql.org/docs/17/sql-syntax-lexical.html
		SELECT 'foo'      'bar';
		
		$function$
		BEGIN
		    RETURN ($1 ~ $q$[\t\r\n\v\\]$q$);
		END;
		$function$
		
		42
		3.5
		4.
		.001
		5e2
		1.925e-3
		
		0b100101
		0B10011001
		0o273
		0O755
		0x42f
		0XFFFF

		1_500_000_000
		0b10001000_00000000
		0o_1_755
		0xFFFF_FFFF
		1.618_034
		
		SELECT COUNT(DISTINCT ausgabe.produkt_id)
		    FROM content, ausgabe
		    WHERE ausgabe.ausgabe_id = content.ausgabe_id
		    AND content.anzeige_id = 47;

		-- Alternative über explizites durchhangeln via Joins
		SELECT az.anzeige_id, COUNT(p.produkt_id)
		FROM anzeige AS az
		    LEFT JOIN content AS c ON c.anzeige_id = az.anzeige_id
		    LEFT JOIN ausgabe AS ag ON ag.ausgabe_id = c.ausgabe_id
		    LEFT JOIN preisliste AS p ON p.produkt_id = ag.produkt_id
		WHERE az.anzeige_id = 47
		GROUP BY az.anzeige_id;
		EOD
	],
	["glsl", <<<'EOD'
		// Fragment shader
		layout(location = 0, index = 0) out vec4 fragment_color;
		layout(location = 0, index = 1) out vec4 blend_weights;
		
		return blend_weights.xxyy + fragment_color.rgba;
		
		// OpenGL setup for "normal" alpha blending (no pre-multiplied alpha)
		glBlendFunc(GL_SRC1_COLOR, GL_ONE_MINUS_SRC1_COLOR);
		// Or for pre-multiplied alpha (see below)
		glBlendFunc(GL_ONE, GL_ONE_MINUS_SRC1_COLOR);
		EOD
	],
	["glsl", <<<'EOD'
		// cond is 1 for negative coverage_adjust_linear values, 0 otherwise.
		// Couldn't think of a good name.
		float cond = float(coverage_adjust_linear < 0);
		float slope = 1 + abs(coverage_adjust_linear);
		pixel_coverage = clamp(cond - (cond - pixel_coverage) * slope, 0, 1);
		EOD
	],
	["glsl", <<<'EOD'
		vec3 color_srgb_to_linear(vec3 rgb) {
			return mix( rgb / 12.92 , pow((rgb + 0.055) / 1.055, vec3(2.4)) , greaterThan(rgb, vec3(0.04045)) );
		}
		
		vec3 color_linear_to_srgb(vec3 rgb) {
			return mix( 12.92 * rgb , 1.055 * pow(rgb, vec3(1 / 2.4)) - 0.055 , greaterThan(rgb, vec3(0.0031308)) );
		}
		EOD
	],
	["shell", <<<'EOD'
		short, 2310 MiByte/s:
		$ grep VrKDJbjoQ9xnzg lines-10m.txt
		medium, 4177 MiByte/s:
		$ grep V6TGqGjjnmhlHRcYEq1IJCgzUNSx09bCkwJnEK lines-10m.txt
		long, 5207 MiByte/s:
		$ grep FMzaoLmVeEoJ3PAPERDFSO2RFEo5/mO17YTQrXz4jr0Ud9w0854q6/rcRu11AocX3vzl4q7O0f6c lines-10m.txt
		EOD
	],
	["ruby", <<<'EOD'
		File.open(filename) do |f|
			f.each_line do |line|
				puts line if line.include? search_term
			end
		end

		# https://ruby-doc.org/3.4.1/syntax/literals_rdoc.html#label-Number+Literals
		1234
		1_234

		0d170
		0D170

		0xaa
		0xAa
		0xAA
		0Xaa
		0XAa
		0XaA

		0252
		0o252
		0O252

		0b10101010
		0B10101010
		1i * 1i     #=> (-1+0i)
		12.3ri      #=> (0+(123/10)*i)

		# https://ruby-doc.org/3.4.1/syntax/literals_rdoc.html#label-Strings
		"This is a string."
		"This string has a quote: \".  As you can see, it is escaped"
		"One plus one is two: #{1 + 1}"
		'#{1 + 1}' #=> "\#{1 + 1}"
		"con" "cat" "en" "at" "ion" #=> "concatenation"
		"This string contains "\
		"no newlines."              #=> "This string contains no newlines."

		expected_result = <<HEREDOC
		This would contain specially formatted text.

		That might span many lines
		HEREDOC
		  expected_result = <<-INDENTED_HEREDOC
		This would contain specially formatted text.

		That might span many lines
		  INDENTED_HEREDOC
		expected_result = <<~SQUIGGLY_HEREDOC
		  This would contain specially formatted text.

		  That might span many lines
		SQUIGGLY_HEREDOC
		puts <<-`HEREDOC`
		cat #{__FILE__}
		HEREDOC

		# https://ruby-doc.org/3.4.1/syntax/literals_rdoc.html#label-Symbol+Literals
		:my_symbol
		:"my_symbol1"
		:"my_symbol#{1 + 1}"
		:"foo\sbar"
		:'my_symbol#{1 + 1}' #=> :"my_symbol\#{1 + 1}"

		# https://ruby-doc.org/3.4.1/syntax/literals_rdoc.html#label-Array+Literals
		[1, [1 + 1, [1 + 2]]]
		{ "a" => 1, "b" => 2 }
		{ a: 1, b: 2 }

		# https://ruby-doc.org/3.4.1/syntax/literals_rdoc.html#label-Regexp+Literals
		re = /foo/ # => /foo/
		/foo \/ bar/

		# https://ruby-doc.org/3.4.1/syntax/literals_rdoc.html#label-25q-3A+Non-Interpolable+String+Literals
		%q[foo bar baz]       # => "foo bar baz" # Using [].
		%q(foo bar baz)       # => "foo bar baz" # Using ().
		%q{foo bar baz}       # => "foo bar baz" # Using {}.
		%q<foo bar baz>       # => "foo bar baz" # Using <>.
		%q|foo bar baz|       # => "foo bar baz" # Using two |.
		%q:foo bar baz:       # => "foo bar baz" # Using two :.
		%q(1 + 1 is #{1 + 1}) # => "1 + 1 is \#{1 + 1}" # No interpolation.
		%q[foo[bar]baz]       # => "foo[bar]baz" # brackets can be nested.
		%q(foo(bar)baz)       # => "foo(bar)baz" # parenthesis can be nested.
		%q{foo{bar}baz}       # => "foo{bar}baz" # braces can be nested.
		%q<foo<bar>baz>       # => "foo<bar>baz" # angle brackets can be nested.
		EOD
	],
	["c", <<<'EOD'
		#include <test.h>

		int x;
		uint8_t x;
		int* x;
		int[] x;
		int**[] x;
		
		"test"
		L"test"
		u8"test"
		u"test"
		U"test"
		
		'test'
		L'test'
		
		// Load the mask image with stb_image.h
		int width = 0, height = 0;
		uint8_t* mask = stbi_load("mask.png", &width, &height, NULL, 1);
		
		// Allocate and create the distance field for the mask. Pixels > 16 in the
		// mask are considered inside. Negative distances in the distance field are
		// inside, positive ones outside.
		float* distance_field = malloc(width * height * sizeof(float));
		sdt_dead_reckoning(width, height, 16, mask, distance_field);
		
		// Create an 8 bit version of the distance field by mapping the distance
		// -128..127 to the brightness 0..255
		uint8_t* distance_field_8bit = malloc(width * height * sizeof(uint8_t));
		for(int n = 0; n < width * height; n++) {
			float mapped_distance = distance_field[n] + 128;
			float clamped_distance = fmaxf(0, fminf(255, mapped_distance))
			distance_field_8bit[n] = clamped_distance;
		}
		EOD
	],
	["glsl", <<<'EOD'
		float smin( float a, float b, float k )
		{
			float h = clamp( 0.5+0.5*(b-a)/k, 0.0, 1.0 );
			return mix( b, a, h ) - k*h*(1.0-h);
		}
		EOD
	],
	["html", <<<'EOD'
		<!DOCTYPE html>
		<title>Switch when loaded</title>
		<link href="blue.css" rel="stylesheet" title="blue">
		<link href="green.css" rel="alternate stylesheet" title="green">
		<script>
			window.addEventListener("load", function(){
				var blue_style = document.querySelector("link[title=blue]");
				var green_style = document.querySelector("link[title=green]");
				
				blue_style.disabled = true;
				green_style.disabled = false;
			});
		</script>
		…
		EOD
	],
		["c", <<<'EOD'
		mat4_t projection = m4_perspective(60, 800.0 / 600.0, 1, 10);
		vec3_t from = vec3(0, 0.5, 2), to = vec3(0, 0, 0), up = vec3(0, 1, 0);
		mat4_t transform = m4_look_at(from, to, up);
		
		vec3_t world_space = vec3(1, 1, -1);
		mat4_t world_to_screen_space = m4_mul(projection, transform);
		vec3_t screen_space = m4_mul_pos(world_to_screen_space, world_space);
		EOD
	],
	["shell", <<<'EOS'
		IP=$( \
			ip addr show dev eth0 scope global | \
			grep --perl-regexp --only-matching '(?<=inet6 )2003:[0-9a-f:]+' | \
			head --lines 1 \
		)
		curl -s http://foo:bar@ns.example.com/?myip=$IP
		EOS
	],
	["c", <<<'EOS'
		#include <stdio.h>
		#include <math.h>
		
		int main() {
			printf("float:\n");
			for (float x = 1.0f, precision; precision < 1.0f; x *= 2.0f) {
				precision = nextafterf(x, INFINITY) - x;
				printf("precision of %f at value %.0f\n", precision, x);
			}
			
			printf("\ndouble:\n");
			for (double x = 1.0, precision; precision < 1.0; x *= 2.0) {
				precision = nextafter(x, INFINITY) - x;
				printf("precision of %lf at value %.0lf\n", precision, x);
			}
			
			return 0;
		}
		EOS
	],
	["shell", <<<'EOS'
		gcc -I. main.c C:\Windows\System32\OpenCL.dll -o main.exe
		EOS
	],
	["shell", <<<'EOS'
		iwatch paper.md paper.css "markdown paper.md && prince -s paper.css paper.html"
		EOS
	]
];

$languages = [
	// Java SE 24 Language Specification: https://docs.oracle.com/javase/specs/jls/se24/html/index.html
	// Pretty much everything from Chapter 3. Lexical Structure: https://docs.oracle.com/javase/specs/jls/se24/html/jls-3.html
	"java" => <<<'EOD'
		(
			(?: (?<keyword> (?: -> | \b (?: abstract | new | assert | package | synchronized | private | this | implements | protected | import | public | throws | enum | instanceof | transient | extends | interface | static | void | class | volatile | native | super | _ | exports | opens | requires | uses | module | permits | sealed | non-sealed | provides | to | open | record | transitive | with ) \b ) )
			|   (?<control> \b (?: continue | for | switch | default | if | do | break | throw | else | case | return | catch | try | final | finally | while | yield | when ) \b )
			|   (?<type>    \b (?: var | boolean | double | byte | int | short | char | long | float | String ) \b (?: \[\] )? )
			|   (?<value>   (?: \b null \b | \b true \b | \b false \b
							|   \b (?i: 0x [0-9a-f_]+ l? | 0b [01_]+ l? ) \b
							|   [-+]? \b (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? [lLfFdD]? \b
							) )
			|   (?<string>  """ [\s\S]*? (?<!\\) """ | ' .*? (?<!\\) ' | " .*? (?<!\\) " )
			|   (?<comment> //.* | /\* [\s\S]+? \*/ )
			|   (?<call>    \b \w+ (?= \( ) )
			)
		)x
	EOD,
	// Data types from https://www.postgresql.org/docs/17/datatype.html
	// Keywords based on highlight/pgsql.js keywords, added some here and there (e.g. key, left, right).
	// Value, string and comment based on https://www.postgresql.org/docs/17/sql-syntax-lexical.html
	"sql" => <<<'EOD'
		(
			(?: (?<keyword> \b (?: abort | alter | analyze | begin | call | checkpoint | close | cluster | comment | commit | copy | create | deallocate | declare | delete | discard | do | drop | end | execute | explain | fetch | grant | import | insert | listen | load | lock | move | notify | prepare | reassign | refresh | reindex | release | reset | revoke | rollback | savepoint | security | select | set | show | start | truncate | unlisten | update | vacuum | values | aggregate | collation | conversion | database | default | privileges | domain | trigger | extension | foreign | wrapper | table | function | group | language | large | object | materialized | view | operator | class | family | policy | publication | role | rule | schema | sequence | server | statistics | subscription | system | tablespace | configuration | dictionary | parser | template | type | user | mapping | prepared | access | method | cast | as | transform | transaction | owned | to | into | session | authorization | index | procedure | assertion | all | analyse | and | any | array | asc | asymmetric | both | case | check | collate | column | concurrently | constraint | cross | deferrable | range | desc | distinct | else | except | for | freeze | from | full | having | ilike | in | initially | inner | intersect | is | isnull | join | lateral | leading | like | limit | natural | not | notnull | null | offset | on | only | or | order | outer | overlaps | placing | primary | references | returning | similar | some | symmetric | tablesample | then | trailing | union | unique | using | variadic | verbose | when | where | window | with | by | returns | inout | out | setof | if | strict | current | continue | owner | location | over | partition | within | between | escape | external | invoker | definer | work | rename | version | connection | connect | tables | temp | temporary | functions | sequences | types | schemas | option | cascade | restrict | add | admin | exists | valid | validate | enable | disable | replica | always | passing | columns | path | ref | value | overriding | immutable | stable | volatile | before | after | each | row | procedural | routine | no | handler | validator | options | storage | oids | without | inherit | depends | called | input | leakproof | cost | rows | nowait | search | until | encrypted | password | conflict | instead | inherits | characteristics | write | cursor | also | statement | share | exclusive | inline | isolation | repeatable | read | committed | serializable | uncommitted | local | global | sql | procedures | recursive | snapshot | rollup | cube | trusted | include | following | preceding | unbounded | range | groups | unencrypted | sysid | format | delimiter | header | quote | encoding | filter | off | force_quote | force_not_null | force_null | costs | buffers | timing | summary | disable_page_skipping | restart | cycle | generated | identity | deferred | immediate | level | logged | unlogged | of | nothing | none | exclude | attribute | usage | routines | true | false | nan | infinity; | alias | begin | constant | declare | end | exception | return | perform | raise | get | diagnostics | stacked | foreach | loop | elsif | exit | while | reverse | slice | debug | log | info | notice | warning | assert | open | key | left | right ) \b )
			|   (?<type>    \b (?: (?: small | big ) (?: int | serial ) | integer | int[248]? | serial[248]? | decimal | numeric | real | double precision | float[48]? | boolean | bool | bit | bit varying | varbit | character | char | character varying | varchar | date | time | timestamp | interval | json | jsonb | xml | numeric | decimal | text ) \b )
			|   (?<value>   (?:
							    (?i: 0x [0-9a-f_]+ | 0b [01_]+ | 0o [0-7_]+ ) 
							|   [-+]? (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )?
							) )
			|   (?<string>  ' .*? ' | \$ [a-z0-9_]+ \$ )
			|   (?<comment> --.* | /\* [\s\S]+? \*/ )
			|   (?<call>    \b \w+ (?= \( ) )
			)
		)xi
	EOD,
	// Everything from The OpenGL® Shading Language Version 4.60.8: https://registry.khronos.org/OpenGL/specs/gl/GLSLangSpec.4.60.pdf
	// Reusing the "string" class for vector swizzle patterns to make the code more interesting.
	"glsl" => <<<'EOD'
		(
			(?: (?<keyword> \b (?: layout | subroutine | const | in | out | inout | attribute | uniform | varying | buffer | shared | centroid | sample | patch | flat | smooth | noperspective | invariant | precise | coherent | volatile | restrict | readonly | writeonly | struct | lowp | mediump | highp | precision | \#\w+ ) \b )
			|   (?<control> \b (?: break | continue | do | for | while | switch | case | default | if | else | discard | return ) \b )
			|   (?<type>    \b (?: 
								int | uint | void | bool |  float | double | atomic_uint |
								[ibud]?vec[234] | d?mat[234](?:x[234])? | [iu]? subpassInput (?: MS )? |
								[iu]? (?: sampler | image | texture ) (?: 1D | 2DRect | 2DMS | 2D | 3D | Cube | Buffer )? (?:Array)? (?:Shadow)?
							) \b )
			|   (?<value>   \b (?: __LINE__ | __FILE__ | __VERSION__ | true | false
							|   (?i: 0x [0-9a-f]+ u? | 0 [0-7]+ u? ) 
							|   [-+]? (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? (?i: f | lf )?
							) )
			|   (?<string>  (?<= \. ) [xyzwrgbastpq]{1,4} )
			|   (?<comment> //.* | /\* [\s\S]+? \*/ )
			|   (?<call>    \b \w+ (?= \( ) )
			)
		)x
	EOD,
	// Keywords from https://ruby-doc.org/3.4.1/syntax/keywords_rdoc.html
	// Can't differentiate easily between keywords and controll flow because "end" is used in both, structure and control flow.
	// Operators from https://ruby-doc.org/3.4.1/syntax/precedence_rdoc.html (but only added .. and ...).
	// Using "control" class for class and instance variables, 
	"ruby" => <<<'EOD'
		(
			(?: (?<keyword> -> | \b \s* (?: __ENCODING__ | __LINE__ | __FILE__ | BEGIN | END | alias | and | begin | break | case | class | def | defined\? | do | else | elsif | end | ensure | false | for | if | in | module | next | nil | not | or | redo | rescue | retry | return | self | super | then | true | undef | unless | until | when | while | yield | \.\.\.? ) \b )
			|   (?<control> @@? \w+ | \$\$? \w+ | \w+ : | \| [^|]+? \| )    # class and instance variabls, keys, argument lists
			|   (?<type>    \b (?: [A-Z][A-Z0-9_]* | [A-Z]\w+ ) \b )        # constants, class names
			|   (?<value>   \b (?:
			                    nil | true | false
			                    | (?i: 0x [0-9a-z_]+ | 0b [01_]+ | 0o [0-7_]+ | 0d [0-9]+ | 0 [0-7]+ )          # prefixed numbers
			                    | [-+]? (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? [ri]{0,2}  # decimal and floating point numbers
			                ) \b
			                | : (?: \w+ | ' .*? (?<!\\) ' | " .*? (?<!\\) " )  # symbols
			                | / .*? (?<!\\) /                                  # regexp
			                )
			|   (?<string>  ' .*? (?<!\\) ' | " .*? (?<!\\) " | <<[\-~]?[\`']?(\w+)[\`']? [\s\S]*? \6
			                | % [qQwWiIsrx] (?: (?<_qp> \( (?: (?>[^()]+) | (?&_qp) )* \) ) | (?<_qs> \[ (?: (?>[^\[\]]+) | (?&_qs) )* \] ) | (?<_qc> \{ (?: (?>[^{}]+) | (?&_qc) )* \} ) | (?<_qa> < (?: (?>[^<>]+) | (?&_qa) )* > ) )
			                )
			|   (?<comment> \#.* | ^ =begin [\s\S]+? ^ =end )
			|   (?<call>    (?: ^ [ \t]* | (?<=\.) ) [\w?!]+ (?= \s+ [^!~+\-*/%<>&|^=.?:] ) | \b [\w?!]+ (?= \( ) )
			)
		)xm
	EOD,
	// Keywords from https://www.man7.org/linux/man-pages/man1/bash.1.html#RESERVED_WORDS
	"shell" => <<<'EOD'
		(
			(?:
			    (?<type>    ^ \w+ = | \$\w+ )
			|   (?<keyword> ^ (?: \$ )? [ \t]* \w+ | \b (?: ! | case |  coproc | do | done | elif | else | esac | fi | for | function | if | in | select | then | until | while | time | \[\[ | \]\] ) \b | \$\( | \) )
			|   (?<control> (?<=\s) (?: -\w | --[a-zA-Z0-9_-]+ (?=\s|$) ) )
			|   (?<value>   \b (?:
			                      (?i: 0x [0-9a-z_]+ | 0b [01_]+ | 0 [0-7]+ )
			                    | [-+]? (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )?
			                ) \b
			                )
			|   (?<string>  ' .*? (?<!\\) ' | " .*? (?<!\\) " )
			|   (?<comment> \#.* )
			)
		)xm
	EOD,
	// Mostly based on the C23 (ISO/IEC 9899:2024 (en) - N3220 working draft): https://www.open-std.org/jtc1/sc22/wg14/www/docs/n3096.pdf
	"c" => <<<'EOD'
		(
			(?: (?<keyword> \b (?: alignas | alignof | constexpr | enum | extern | inline | register | restrict | signed | sizeof | static | static_assert | struct | thread_local | typedef | typeof | typeof_unqual | union | unsigned | volatile | _Atomic | _Generic | _Imaginary | _Noreturn ) \b
			                | \#\w+ | \&
			                )
			|   (?<control> \b (?: if | else | for | do | while | break | continue | switch | case | default | return | goto ) \b )
			|   (?<type>    \b (?: auto | bool | char | const | double | float | int | long | short | void | _BitInt | _Complex | _Decimal(?:128|64|32) | \w+_t ) \b
			                (?: \* | \[\] | \[ \d+ \] )*
			                )
			|   (?<value>   \b (?: __LINE__ | __FILE__ | TRUE | true | FALSE | false | NULL | nullptr
			                |   (?i: 0x [0-9a-f]+ | 0b [01] | 0 [0-7]+ ) [wbulWBUL]*
			                |   [-+]? (?: \d [\d_]* (?: \. \d+ )? | \. \d+ ) (?: [eE] [-+]? \d+ )? [lfd]*
			                ) )
			|   (?<string>  (?: L | u8 | u | U )? (?: ' .*? (?<!\\) ' | " .*? (?<!\\) " )
			                | (?<= \#include ) \s+ < [^>]+ > )
			|   (?<comment> //.* | /\* [\s\S]+? \*/ )
			|   (?<call>    \b \w+ (?= \( ) )
			)
		)x
	EOD,
	// HTML element and attribute names from https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements
	// Selected the ol in the nav sidebar with the element names in the developer tools. Then extracted them via the console:
	// console.log(Array.from($0.querySelectorAll("li:not(:has(.icon-deprecated)):not(:has(.icon-experimental))")).map(li => li.textContent.trim().replaceAll(/^<|>$/g, "")).join(" | "))
	// Same for the "Attributes" and "Global attributes", albeit some were missing (e.g. href).
	// Then sorted tag and attribute names by length: "… | … | …".split(" | ").toSorted((a, b) => b.length - a.length).join(" | ")
	"html" => <<<'EOD'
		(
			(?: (?<string>
			        (?<= <script> | <script type=module> | <script type="module"> ) [\s\S]*? (?= </script> )
			    |   (?<= <style> )  [\s\S]*? (?= </style> )
			    )
			|   (?<keyword>
			        < /? (?i: blockquote | figcaption | colgroup | datalist | fieldset | noscript | optgroup | progress | template | textarea | address | article | caption | details | picture | section | summary | button | canvas | dialog | figure | footer | header | hgroup | iframe | legend | object | option | output | script | search | select | source | strong | aside | audio | embed | input | label | meter | small | style | table | tbody | tfoot | thead | title | track | video | abbr | area | base | body | cite | code | data | form | head | html | link | main | mark | menu | meta | ruby | samp | slot | span | time | bdi | bdo | col | del | dfn | div | img | ins | kbd | map | nav | pre | sub | sup | var | wbr | br | dd | dl | dt | em | h1 | hr | li | ol | rp | rt | td | th | tr | ul | a | b | i | p | q | s | u ) >?
			    |   (?i: <!doctype )
			    |   >
			    )
			|   (?<type> (?i: data-[-\S]+ | writingsuggestions | contenteditable | autocapitalize | elementtiming | autocomplete | enterkeyhint | crossorigin | placeholder | autocorrect | exportparts | spellcheck | maxlength | minlength | accesskey | autofocus | draggable | inputmode | itemscope | translate | disabled | multiple | readonly | required | itemprop | itemtype | tabindex | capture | dirname | pattern | itemref | popover | accept | hidden | itemid | class | inert | nonce | style | title | href | size | step | lang | part | slot | for | max | min | rel | dir | id | is ) )
			    (?: = (?<value> (?: " [^"]*? " | ' [^']*? ' | \S+ ) ) )?
			|   (?<comment> <!-- [\s\S]+? --> )
			)
		)x
	EOD
];

function highlight($code, $language_name, $language_definitions) {
	if ( !array_key_exists($language_name, $language_definitions) )
		return htmlspecialchars($code, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML5);
	$regexp = $language_definitions[$language_name];
	
	$result = "";
	$offset = 0;
	while ( preg_match($regexp, $code, $match, PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL, $offset) === 1 ) {
		foreach ($match as $group_name => [$group_text, $group_offset]) {
			if ( !is_int($group_name) && $group_name[0] != "_" && $group_text !== null ) {
				$text_before_match = substr($code, $offset, $group_offset - $offset);
				
				$result .= htmlspecialchars($text_before_match, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML5);
				$escaped_group_text = htmlspecialchars($group_text, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML5);
				$result .= "<span class=\"$group_name\">$escaped_group_text</span>";
				
				$offset = $group_offset + strlen($group_text);
			}
		}
	}
	$result .= htmlspecialchars(substr($code, $offset), ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML5);
	
	return $result;
}

?>
<!DOCTYPE html>
<meta charset=utf-8>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Small Syntax Highlighting</title>
<style>
	body { background-color: #151515; color: #d0d0d0; tab-size: 4; }
	span.value    { color: #75b5aa; }
	span.keyword  { color: #f4bf75; }
	span.control  { color: #aa759f; font-weight: bold; }
	span.type     { color: #d28445; }
	span.comment  { color: #848484; }
	span.string   { color: #90a959; }
	span.call     { color: #6a9fb5; }
</style>

<?php foreach($code_examples as [$language_name, $code]): ?>
<pre><?= highlight($code, $language_name, $languages) ?></pre>
<hr>
<?php endforeach ?>