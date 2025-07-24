<?php

require_once("simple_syntax_highlighting.php");

$code_examples = [
	["java", "Nested loops with math", <<<'EOD'
		// Code from https://rosettacode.org/wiki/Draw_a_sphere#Java
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
	["java", "Small HTTP server", <<<'EOD'
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
	["java", "SQLite JDBC example", <<<'EOD'
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
	["java", "Development sketchpad", <<<'EOD'
		// Single line comment
		// HTML escaping <b>test</b>.
		/* multiline
		comment */
		/* multiline comment */
		
		// Numbers and values
		+16 x -16 x
		3.141 x +3.141 x -3.141 x 
		3.234e17 x +3.234e17 x -3.234e17 - 15
		0xffaa_0011 x 0XFFAA_0011 x
		0b0011_0011 x 0B0011_0011 x
		x null 
		
		// Strings
		x 'test' x '' x 'a \' b' x
		x "test" x "" x "a \" b" x
		
		// Text blocks
		String text = """
			The quick brown fox jumps over the lazy dog
		""";
		
		String code =
			"""
			String text = \"""
				The quick brown fox jumps over the lazy dog
			\""";
			""";
		
		// Keywords
		import java.nio.file.*;
		class Code {
			public static void main(String[] args) {
			}
		}
		EOD
	],
	["sql", "Development sketchpad", <<<'EOD'
		-- Basic statements with keywords and types
		CREATE TABLE posts (
			id       INTEGER PRIMARY KEY,
			topic_id INTEGER,
			body     TEXT
		);
		CREATE INDEX posts_topic_index ON posts (topic_id);
		
		-- Strings
		SELECT 'foo'      'bar';
		
		$function$
		BEGIN
		    RETURN ($1 ~ $q$[\t\r\n\v\\]$q$);
		END;
		$function$
		
		-- Numbers
		42
		3.5
		4.    -- to lazy for that
		.001  -- to lazy for that
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
		
		-- More complex statements
		SELECT COUNT(DISTINCT ausgabe.produkt_id)
		    FROM content, ausgabe
		    WHERE ausgabe.ausgabe_id = content.ausgabe_id
		    AND content.anzeige_id = 47;

		SELECT az.anzeige_id, COUNT(p.produkt_id)
		FROM anzeige AS az
		    LEFT JOIN content AS c ON c.anzeige_id = az.anzeige_id
		    LEFT JOIN ausgabe AS ag ON ag.ausgabe_id = c.ausgabe_id
		    LEFT JOIN preisliste AS p ON p.produkt_id = ag.produkt_id
		WHERE az.anzeige_id = 47
		GROUP BY az.anzeige_id;
		EOD
	],
	["glsl", "Development sketchpad", <<<'EOD'
		layout(location = 0, index = 0) out vec4 fragment_color;
		layout(location = 0, index = 1) out vec4 blend_weights;
		
		// Swizzle patterns
		return blend_weights.xxyy + fragment_color.rgba;
		return vec4(pos.xyz, 1);
		
		// Random code
		// cond is 1 for negative coverage_adjust_linear values, 0 otherwise.
		// Couldn't think of a good name.
		float cond = float(coverage_adjust_linear < 0);
		float slope = 1 + abs(coverage_adjust_linear);
		pixel_coverage = clamp(cond - (cond - pixel_coverage) * slope, 0, 1);
		
		// Functions
		vec3 color_srgb_to_linear(vec3 rgb) {
			return mix( rgb / 12.92 , pow((rgb + 0.055) / 1.055, vec3(2.4)) , greaterThan(rgb, vec3(0.04045)) );
		}
		
		vec3 color_linear_to_srgb(vec3 rgb) {
			return mix( 12.92 * rgb , 1.055 * pow(rgb, vec3(1 / 2.4)) - 0.055 , greaterThan(rgb, vec3(0.0031308)) );
		}
		
		float smin( float a, float b, float k )
		{
			float h = clamp( 0.5+0.5*(b-a)/k, 0.0, 1.0 );
			return mix( b, a, h ) - k*h*(1.0-h);
		}
		EOD
	],
	["ruby", "Development sketchpad", <<<'EOD'
		# Random code
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
		%q|foo bar baz|       # => "foo bar baz" # Using two |. # to lazy for that
		%q:foo bar baz:       # => "foo bar baz" # Using two :. # to lazy for that
		%q(1 + 1 is #{1 + 1}) # => "1 + 1 is \#{1 + 1}" # No interpolation.
		%q[foo[bar]baz]       # => "foo[bar]baz" # brackets can be nested.
		%q(foo(bar)baz)       # => "foo(bar)baz" # parenthesis can be nested.
		%q{foo{bar}baz}       # => "foo{bar}baz" # braces can be nested.
		%q<foo<bar>baz>       # => "foo<bar>baz" # angle brackets can be nested.
		
		%[foo bar baz]        # => "foo bar baz"
		%(1 + 1 is #{1 + 1})  # => "1 + 1 is 2" # Interpolation.
		EOD
	],
	["c", "Small program with for loops", <<<'EOD'
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
		EOD
	],
	["c", "Development sketchpad", <<<'EOD'
		// Preprocessor directives
		#include <test.h>
		
		// Types
		int x;
		uint8_t x;
		int* x;
		int[] x;
		int**[] x;
		
		// Strings
		"test"
		L"test"
		u8"test"
		u"test"
		U"test"
		
		// Character literals
		'test'
		L'test'
		
		// Function from https://rosettacode.org/wiki/Factorial#C
		int factorialSafe(int n) {
		    int result = 1;
		    if(n<0)
		        return -1;
		    for (int i = 1; i <= n; ++i)
		        result *= i;
		    return result;
		}
		
		// Random code
		
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
		
		mat4_t projection = m4_perspective(60, 800.0 / 600.0, 1, 10);
		vec3_t from = vec3(0, 0.5, 2), to = vec3(0, 0, 0), up = vec3(0, 1, 0);
		mat4_t transform = m4_look_at(from, to, up);
		
		vec3_t world_space = vec3(1, 1, -1);
		mat4_t world_to_screen_space = m4_mul(projection, transform);
		vec3_t screen_space = m4_mul_pos(world_to_screen_space, world_space);
		EOD
	],
	["bash", "Simple command", <<<'EOD'
		gcc -I. main.c C:\Windows\System32\OpenCL.dll -o main.exe
		EOD
	],
	["bash", "Command in string", <<<'EOD'
		iwatch paper.md paper.css "markdown paper.md && prince -s paper.css paper.html"
		EOD
	],
	["bash", "Variables and subshell", <<<'EOD'
		IP=$( \
			ip addr show dev eth0 scope global | \
			grep --perl-regexp --only-matching '(?<=inet6 )2003:[0-9a-f:]+' | \
			head --lines 1 \
		)
		curl -s http://foo:bar@ns.example.com/?myip=$IP
		EOD
	],
	["html", "Development sketchpad", <<<'EOS'
		<!DOCTYPE html>
		<meta charset=utf-8>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Development sketchpad</title>
		<link href="blue.css" rel=stylesheet title=blue>
		<link href="green.css" rel="alternate stylesheet" title="green">
		<!-- comment -->
		
		<pre>xyz</pre>
		<div></div>
		
		<script type=module>
			window.addEventListener("load", function(){
				var blue_style = document.querySelector("link[title=blue]");
				var green_style = document.querySelector("link[title=green]");
				
				console.log("</script>")
				console.log('</script>')
				console.log(`</script>`)
				
				blue_style.disabled = true;
				green_style.disabled = false;
				// Workaround for Chromium and WebKit bug (Chromium issue 843887)
				green_style.disabled = true;
				green_style.disabled = false;
			});
		</script>
		<script>
			1n + 7
			console.log("</script>")
		</script>
		
		<style>
			body { background-color: #151515; color: #d0d0d0; font: 14px sans-serif; }
			div {
				margin-block: 2lh; display: flex; gap: 1lh;
				& span { flex: 0 0 100px; height: 100px; color: black; }
			}
		</style>
		
		<?php
			$message = <<<'EOD'
			From: "Mr. Sender" <sender@dep1.example.com>
			To: "Mr. Receiver" <receiver@dep2.example.com>

			?>
			EOD;
			smtp_send('sender@dep1.example.com', 'receiver@dep2.example.com', $message,
				'mail.dep1.example.com', 587,
				array('user' => 'sender', 'pass' => 'secret')
			);
			"?>"
			'?>'
		?>test
		<h1>foo</h1>
		<?= "foo" ?>
		<?php foreach($foo as $bar): ?>
		<li><? array($bar, 2, 3) ?></li>
		<?php endforeach ?>
		<!--
		<?= "foo" ?>
		-->
		
		EOS
	],
	["js", "Development sketchpad", <<<'EOD'
		const str = "sociis natoque penatibus magnis parturient montes nascetur ridiculus"
		const regexp = /foo \/ bar/g
		
		// Colors from https://github.com/mzlogin/rouge-themes/blob/master/dist/base16.dark.css, preview at https://github.com/mzlogin/rouge-themes/tree/master?tab=readme-ov-file#base16dark
		const styles = [
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
		
		document.querySelector("button").addEventListener("click", async event => console.log(event))
		
		async function doSomething(arg1, arg2) {
			await backgroundJob()
			console.log("foo bar")
		}
		EOD
	],
	["css", "Development sketchpad", <<<'EOD'
		body { background-color: #151515; color: #d0d0d0; font: 14px sans-serif; }
		div {
			margin-block: 2lh; display: flex; gap: 1lh;
			& span { flex: 0 0 100px; height: 100px; color: black; }
		}
		test:not(:has(foo)) { color: red; }
		:is(h1, h2, h3) { color: blue; }
		a:active { color: blue; }

		custom-element { width: 20cm; }
		@page{ width: 20cm; }
		EOD
	],
	["php", "Development sketchpad", <<<'EOS'
		// Random code
		$data = file_get_contents('../mails.txt');
		$line = strtok($data, "\r\n");
		while ($line !== false) {
			$line = strtok("\r\n");
			// do something with $line
		}
		
		// Heredoc string
		$message = <<<EOD
			From: "Mr. Sender" <sender@dep1.example.com>
			To: "Mr. Receiver" <receiver@dep2.example.com>
			Subject: SMTP Test
			Date: Thu, 21 Dec 2017 16:01:07 +0200
			Content-Type: text/plain; charset=utf-8
			
			Hello there. Just a small test. 😊
			End of message.
			EOD;
		
		// Function call
		smtp_send('sender@dep1.example.com', 'receiver@dep2.example.com', $message,
			'mail.dep1.example.com', 587,
			array('user' => 'sender', 'pass' => 'secret')
		);
		EOS
	]
];

?>
<!DOCTYPE html>
<meta charset=utf-8>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Simple Syntax Highlighting</title>
<style>
	body { background-color: #151515; color: #d0d0d0; font-family: sans-serif; }
	
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

<?php foreach($code_examples as [$language_name, $description, $code]): ?>
<p><?= $language_name ?>: <?= htmlspecialchars($description) ?></p>
<pre><code class=lang_<?= $language_name ?>><?= simple_syntax_highlighting($code, $language_name) ?></code></pre>
<hr>
<?php endforeach ?>

<p>No fuss example:</p>
<pre><code class=lang_glsl><?= simple_syntax_highlighting("return vec4(pos.xyz, 1);", "glsl") ?></code></pre>