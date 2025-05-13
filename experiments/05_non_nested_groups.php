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
		// Run with: java MiniHTTPServer.java
		import com.sun.net.httpserver.*;
		import java.nio.file.*;
		import static java.nio.charset.StandardCharsets.UTF_8;

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
		#include "foo.h"
		
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
	["commands", <<<'EOD'
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

		=begin
		multiline
		comment
		=end

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

		1..3 1...3
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
		
		+1 +1.5 1 1.5 -1 -1.5 +-1.0 -+1.0
		*deref *(deref)


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
	["html", <<<'EOD'
		<!DOCTYPE html>
		<title>Switch when loaded</title>
		<link href="blue.css" rel="stylesheet" title="blue">
		<link href="green.css" rel="alternate stylesheet" title="green">
		<script type=module>
			window.addEventListener("load", function(){
				var blue_style = document.querySelector("link[title=blue]");
				var green_style = document.querySelector("link[title=green]");
				
				console.log("</script>")
				console.log('</script>')
				console.log(`</script>`)
				console.log("test \"foo\"")
				
				blue_style.disabled = true;
				green_style.disabled = false;
				// Workaround for Chromium and WebKit bug (Chromium issue 843887)
				green_style.disabled = true;
				green_style.disabled = false;
			});
		</script>
		…
		EOD
	],
	["php", <<<'EOS'
		$message = <<<EOD
		From: "Mr. Sender" <sender@dep1.example.com>
		To: "Mr. Receiver" <receiver@dep2.example.com>
		Subject: SMTP Test
		Date: Thu, 21 Dec 2017 16:01:07 +0200
		Content-Type: text/plain; charset=utf-8

		Hello there. Just a small test. 😊
		End of message.
		EOD;
		smtp_send('sender@dep1.example.com', 'receiver@dep2.example.com', $message,
			'mail.dep1.example.com', 587,
			array('user' => 'sender', 'pass' => 'secret')
		);
		EOS
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
	["bash", <<<'EOS'
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
	["bash", <<<'EOS'
		gcc -I. main.c C:\Windows\System32\OpenCL.dll -o main.exe
		EOS
	],
	["bash", <<<'EOS'
		iwatch paper.md paper.css "markdown paper.md && prince -s paper.css paper.html"
		EOS
	],
	["php", <<<'EOS'
		$data = file_get_contents('../mails.txt');
		$line = strtok($data, "\r\n");
		while ($line !== false) {
			$line = strtok("\r\n");
			// do something with $line
		}
		EOS
	],
	["html", <<<'EOS'
		<!DOCTYPE html>
		<meta charset=utf-8>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Source Code Color Test</title>
		<style>
			body { background-color: #151515; color: #d0d0d0; font: 14px sans-serif; }
			div {
				margin-block: 2lh; display: flex; gap: 1lh;
				& span { flex: 0 0 100px; height: 100px; color: black; }
			}
		</style>

		<pre></pre>
		<div></div>
		<script type=module>
			const line = "sociis natoque penatibus magnis parturient montes nascetur ridiculus"
			// Colors from https://github.com/mzlogin/rouge-themes/blob/master/dist/base16.dark.css, preview at https://github.com/mzlogin/rouge-themes/tree/master?tab=readme-ov-file#base16dark
			const styles = [
				"color: #87939d;",  // gray, good for comments.
				"color: #d0d0d0; background-color: #151515;",  // normal text color
				"color: #151515; background-color: #ac4142;",  // red background, dark text. very strong error highlight.
				"color: #848484;",  // slightly darker gray, even better for comments.
				"color: #f4bf75;",
				"color: #90a959;",
				"color: #f08a8b; background-color: #320000;",
				"color: #6a9fb5; background-color: #151515; font-weight: bold;",
				"color: #aa759f;",
				"color: #d28445;",
				"color: #75b5aa;",
				"color: #b76d45;",
				"color: #6a9fb5;",
			]
			
			const pre = document.querySelector("pre")
			for (const style of styles) {
				const words = line.split(/\s/)
				const randomIndex = Math.floor(Math.random() * words.length)
				words[randomIndex] = `<span style="${style}">${words[randomIndex]}</span>`
				pre.innerHTML += words.join(" ") + "\n"
			}
			
			const div = document.querySelector("div")
			for (const style of styles) {
				const color = style.match(/color: ([^;]+);/)[1]
				const span = document.createElement("span")
					span.textContent = color
					span.setAttribute("style", `background-color: ${color};`)
				div.append(span)
			}
			
			const regexp = /foo \/ bar/g
		</script>
		EOS
	],
	["css", <<<'EOS'
		body { background-color: #151515; color: #d0d0d0; font: 14px sans-serif; }
		div {
			margin-block: 2lh; display: flex; gap: 1lh;
			& span { flex: 0 0 100px; height: 100px; color: black; }
		}
		test:not(:has(foo)) { color: red; }
		:is(h1, h2, h3) { color: blue; }
		a:active { color: blue; }
		
		h1::before { content: 'test'; }

		custom-element { width: 20cm; }
		@page{ width: 20cm; }
		EOS
	],
	["html", <<<'EOS'
		<title>foo</title>
		<script>
			1n + 7
			console.log("</script>")
		</script>
		<style>
			h1 { color: red; }
		</style>
		<?php
			$message = <<<'EOD'
			From: "Mr. Sender" <sender@dep1.example.com>
			To: "Mr. Receiver" <receiver@dep2.example.com>

			Hello there. Just a small test. 😊
			EOD;
			smtp_send('sender@dep1.example.com', 'receiver@dep2.example.com', $message,
				'mail.dep1.example.com', 587,
				array('user' => 'sender', 'pass' => 'secret')
			);
		?>test<h1>foo</h1>
		<?php foreach($foo as $bar): ?>
		<li><?= array($bar, 2, 3) ?></li>
		<?php endforeach ?>
		<!--
		<?= "foo" ?>
		-->
		EOS
	]
];

$languages = [
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
		    |   % [qQwWiIsrx]
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
		    (?<variation_c>  ^ [ \t]* \w+ )
		|   # Comments
		    (?<backdrop_a>   \# .* )
		)xm
	EOD,
	// Mostly based on the C23 (ISO/IEC 9899:2024 (en) - N3220 working draft): https://www.open-std.org/jtc1/sc22/wg14/www/docs/n3096.pdf
	"c" => <<<'EOD'
		(
		    # Non-control-flow keywords, preprocessor directives and important operators
		    (?<landmark_a>
		        \b (?: alignas | alignof | constexpr | enum | extern | inline | register | restrict | signed | sizeof | static | static_assert | struct | thread_local | typedef | typeof | typeof_unqual | union | unsigned | volatile | _Atomic | _Generic | _Imaginary | _Noreturn ) \b
		    |   \#\w+
		    |   \&
		    |   \* (?= \w | \( )
		    )
		|   # Control-flow keywords
		    (?<landmark_b> \b (?: if | else | for | do | while | break | continue | switch | case | default | return | goto ) \b )
		|   # Types
		    (?<landmark_c>
		        \b (?: auto | bool | char | const | double | float | int | long | short | void | _BitInt | _Complex | _Decimal(?:128|64|32) | \w+_t ) \b
		        (?: \* | \[\] | \[ \d+ \] )*
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
		    (?<landmark_c01> <script ) (?<lang_html_attr0> [^>]+ )? (?<landmark_c02> > ) (?<lang_js> [\s\S]*? (?: (?: " [^"]*? " | ' [^']*? ' | ` [^`]*? ` ) [\s\S]*? )* ) (?<landmark_c03> </script> )
		|   # Embedded CSS
		    (?<landmark_c11> <style ) (?<lang_html_attr1> [^>]+ )? (?<landmark_c12> > ) (?<lang_css> [\s\S]*? ) (?<landmark_c13> </style> )
		|   # Embedded PHP
		    (?<landmark_c21> <\? (?: php | = )? ) (?<lang_php> [\s\S]*? (?: (?: " [^"]*? " | ' [^']*? ' | <<< '? (?<_id> \w+ ) '? \n [\s\S]+? \g{_id} ) [\s\S]*? )* ) (?<landmark_c22> \?> )
		|   # HTML start tags
		    (?<landmark_c31> < !? \w+ ) (?<lang_html_attr2> [^>]+ )? (?<landmark_c32> > )
		|   # HTML end tags
		    (?<landmark_c>  </ \w+ > )
		|   # Comments
		    (?<backdrop_a>  <!-- [\s\S]+? --> )
		)x
	EOD,
	"html_attr" => <<<'EOD'
		(
		    # Attribute name
		    (?<variation_a> \w+ )
		    # Optionally followed by a value
		    (?: = (?<variation_b> " [^"]*? " | ' [^']*? ' | \S+ ) )?
		)x
	EOD,
	// Mostly based on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Lexical_grammar
	"js" => <<<'EOD'
		(
		    # Non-control-flow keywords
		    (?<landmark_a>  \b (?: import | as | from | export | function | async | class | extends | static | implements | interface | package | private | protected | public | get | set | this | super | new | delete | instanceof | typeof | enum | void ) \b )
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

// Based on highlight_32_no_nested_groups_no_str_starts_with() from 03_benchmarking.php
function highlight_32_no_nested_groups_no_str_starts_with($code, $language_name) {
	global $languages;
	if ( !array_key_exists($language_name, $languages) )
		return htmlspecialchars($code, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML5);
	$regexp = $languages[$language_name];
	
	preg_match_all($regexp, $code, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL);
	
	$result = "";
	$pos = 0;
	foreach ($matches as $match_index => $match) {
		foreach ($match as $group_name => [$group_text, $group_start_offset]) {
			// Skip numbered groups, groups starting with "_" or groups that match nothing.
			if ( is_int($group_name) or $group_name[0] == "_" or $group_text === null )
				continue;
			
			$text_before_match = substr($code, $pos, $group_start_offset - $pos);
			$result .= htmlspecialchars($text_before_match);
			
			// Remove numbers from the end of group names, so we can effectively use the same group name multiple times in one match.
			$group_name = rtrim($group_name, "0123456789");
			
			// This comparison is roughly 20% faster than str_starts_with() on PHP 8.1.2-1ubuntu2.21 (cli)
			if ( $group_name[0] == "l" and $group_name[1] == "a" and $group_name[2] == "n" and $group_name[3] == "g" and $group_name[4] == "_" ) {
				// If a group is something like "lang_css" process the group text recursively. That way we can nest languages as long as the
				// end of the nested region is known before the recursion.
				$nested_language = substr($group_name, 5);
				$result .= "<span data-s=$group_name>" . highlight_32_no_nested_groups_no_str_starts_with($group_text, $nested_language) . "</span>";
			} else {
				// Start a span for every normal named group in a match. The end of the span is inserted by the $end_offsets logic above and
				// below. That way we get nested spans for nested named groups in the regexp.
				$result .= "<span data-s=$group_name>" . htmlspecialchars($group_text) . "</span>";
			}
			
			$pos = $group_start_offset + strlen($group_text);
		}
	}
	
	$result .= htmlspecialchars(substr($code, $pos));
	
	return $result;
}

// Based on highlight_31_nested_groups() from 03_benchmarking.php
function highlight_31_nested_groups($code, $start_lang, $debug = false) {
	global $languages;
	if ( !isset($languages[$start_lang]) )
		return htmlspecialchars($code);
	$regexp = $languages[$start_lang];
	
	preg_match_all($regexp, $code, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL);
	
	if ($debug === true)
		$debug = 0;
	if (is_int($debug))
		printf("%spos  end_offsets  match:group         offsets  code\n", str_repeat("    ", $debug));
	
	$pos = 0;
	$result = "";
	$end_offsets = [];
	foreach ($matches as $match_index => $match) {
		foreach ($match as $group_name => [$group_text, $group_start_offset]) {
			// Skip numbered groups, groups starting with "_" or groups that match nothing.
			if ( is_int($group_name) or $group_name[0] == "_" or $group_text === null )
				continue;
			
			if (is_int($debug)) {
				// Output a match trace for debugging if you want
				printf("%s%-4d [%-9s] %3d:%-15s %4d-%-4d %s\n", str_repeat("    ", $debug),
					$pos, join(", ", $end_offsets), $match_index, $group_name,
					$group_start_offset, $group_start_offset + strlen($group_text),
					htmlspecialchars(str_replace("\n", " ", $group_text))
				);
			}
			
			// End spans of previous groups if they ended before the current group starts
			while ( !empty($end_offsets) and $end_offsets[0] <= $group_start_offset ) {
				$prev_group_end_offset = array_shift($end_offsets);
				$prev_group_text = substr($code, $pos, $prev_group_end_offset - $pos);
				$pos = $prev_group_end_offset;
				$result .= htmlspecialchars($prev_group_text) . "</span>";
			}
			
			// Emit escaped text until this group starts
			$text_before_group = substr($code, $pos, $group_start_offset - $pos);
			$pos = $group_start_offset;
			$result .= htmlspecialchars($text_before_group);
			
			// Remove numbers from the end of group names, so we can effectively use the same group name multiple times in one match.
			$group_name = rtrim($group_name, "0123456789");
			if ( str_starts_with($group_name, "lang_") ) {
				// If a group is something like "lang_css" process the group text recursively. That way we can nest languages as long as the
				// end of the nested region is known before the recursion.
				$nested_language = substr($group_name, 5);
				$result .= "<span data-s=$group_name>" . highlight_31_nested_groups($group_text, $nested_language, debug: is_int($debug) ? $debug + 1 : false) . "</span>";
				$pos += strlen($group_text);
			} else {
				// Start a span for every normal named group in a match. The end of the span is inserted by the $end_offsets logic above and
				// below. That way we get nested spans for nested named groups in the regexp.
				$result .= "<span data-s=$group_name>";
				$group_end_offset = $group_start_offset + strlen($group_text);
				array_unshift($end_offsets, $group_end_offset);
			}
		}
	}
	
	// End any pending spans
	while ( !empty($end_offsets) ) {
		$prev_group_end_offset = array_shift($end_offsets);
		$prev_group_text = substr($code, $pos, $prev_group_end_offset - $pos);
		$pos = $prev_group_end_offset;
		$result .= htmlspecialchars($prev_group_text) . "</span>";
	}
	
	// Add the code between the end of the last named group and the end of string
	$result .= htmlspecialchars(substr($code, $pos));
	
	return $result;
}



?>
<!DOCTYPE html>
<meta charset=utf-8>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Small Syntax Highlighting</title>
<style>
	body { background-color: #151515; color: #d0d0d0; tab-size: 4; }
	span[data-s=value]     { color: #75b5aa; }
	span[data-s=keyword]   { color: #f4bf75; }
	span[data-s=control]   { color: #aa759f; font-weight: bold; }
	span[data-s=type]      { color: #d28445; }
	span[data-s=comment]   { color: #848484; }
	span[data-s=string]    { color: #90a959; }
	span[data-s=call]      { color: #6a9fb5; }
	span[data-s=lang_java] { color: hsl(200deg 50% 80%); font-style: italic; }
	
	/* Colors based on base16.dark (https://github.com/mzlogin/rouge-themes/tree/master?tab=readme-ov-file#base16dark) and base16-edge-dark (https://highlightjs.org/demo) */
	pre[data-s]              { background-color: #151515; color: #d0d0d0; tab-size: 4;  }
	span[data-s=landmark_a]  { color: #d28445; } /* #e77171 from base16-edge-dark is a bit to eye-catching */
	span[data-s=landmark_b]  { color: #d390e7; }
	span[data-s=landmark_c]  { color: #dbb774; }
	span[data-s=variation_a] { color: #5ebaa5; }
	span[data-s=variation_b] { color: #90a959; }
	span[data-s=variation_c] { color: #73b3e7; }
	span[data-s=backdrop_a]  { color: #848484; }
	span[data-s=backdrop_b]  { color: #87939d; }
	/*
		landmark A, B, C   → landmark_a1  lma1 lma2 lmb1
		variation A, B, C  → variation_a1 va vb1 vb2
		backdrop A, B      → backdrop_a   ba bb
	*/
</style>

<pre data-s=<?= $code_examples[21][0] ?>><?= highlight_32_no_nested_groups_no_str_starts_with($code_examples[21][1], $code_examples[21][0]) ?></pre>
<hr>

<?php $start = microtime(true) ?>

<?php foreach($code_examples as $index => [$language, $code]): ?>
<p>[<?= $index ?>] Language: <?= $language ?></p>
<pre data-s=<?= $language ?>><?= highlight_32_no_nested_groups_no_str_starts_with($code, $language) ?></pre>
<hr>
<?php endforeach ?>

<pre data-s=html><?= highlight_32_no_nested_groups_no_str_starts_with(file_get_contents("03_benchmarking.php"), "html") ?></pre>
<hr>

<?php $duration = microtime(true) - $start ?>
<b><?php printf("duration: %.2fms\n", $duration * 1000) ?></b>