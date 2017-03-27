<?php
	//HTML Form handling
	if($_GET)
	{
		$textInput = $_GET["textInput"];
		$errorMsg = checkEquationSyntax($textInput);
		if($errorMsg == "")
		{
			$textInput = processEquationSyntax($textInput);
			$result = calculateEquation($textInput);
		}
		else
		{
			$result = $errorMsg;
		}
	}
	
	//Checks the string for illegal characters and unmatched brackets, returns error message
	function checkEquationSyntax($text)
	{
		$errorMsg = "";
		
		
		//CHECKING FOR UNMATCHED PARANTHESES
		$openParanthesesCounter = 0;
		$closedParanthesesCounter = 0;
		for($i = 0; $i < strlen($text); $i++)
		{
			if($text[$i] == '(')
			{
				$openParanthesesCounter++;
			}
			else if($text[$i] == ')')
			{
				$closedParanthesesCounter++;
			}
			
			//Checks if string doesn't start with closing parantheses e.g. )(
			if($closedParanthesesCounter > $openParanthesesCounter)
			{
				$errorMsg = "<strong>Error</strong>: Parantheses syntax error";
			}
		}
		if($openParanthesesCounter != $closedParanthesesCounter)
		{
			$errorMsg = "<strong>Error</strong>: Found unmatched parantheses";
		}
		
		
		//CHECKING FOR ILLEGAL CHARACTERS
		$regExp = "/[^0-9\(\)\*\/\+\-\.\s\,]/";
		$matchesCount = preg_match_all($regExp, $text, $matchResult);
		if ($matchesCount > 0)
		{
			$errorMsg = "<strong>Error</strong>: Found illegal character(s): ";
			for($i = 0; $i < $matchesCount; $i++)
			{
				$errorMsg = $errorMsg . $matchResult[0][$i];
			}
		}
		
		
		//CHECKING FOR MULTIPLE OPERATORS IN A ROW e.g. **, */, *+, +-
		$regExp = "/[\*\/\+\-\.\,]{2,}/";
		$matchesCount = preg_match_all($regExp, $text, $matchResult);
		if ($matchesCount > 0)
		{
			$errorMsg = "<strong>Error</strong>: Found illegal syntax: ";
			for($i = 0; $i < $matchesCount; $i++)
			{
				$errorMsg = $errorMsg . $matchResult[0][$i];
			}
		}
		//CHECKING FOR OPERATOR/PARANTHESIS ERRORS e.g. (/, (*, (+
		$regExp = "/\([\*\/\+\.\,]/";
		$matchesCount = preg_match_all($regExp, $text, $matchResult);
		if ($matchesCount > 0)
		{
			$errorMsg = "<strong>Error</strong>: Found illegal syntax: ";
			for($i = 0; $i < $matchesCount; $i++)
			{
				$errorMsg = $errorMsg . $matchResult[0][$i];
			}
		}
		
		
		return $errorMsg;
	}
	
	//Returns formatted string ready for calculation, without whitespaces etc.
	function processEquationSyntax($text)
	{
		//REMOVING SPACES
		$text = str_replace(" ", "", $text);
		//CHANGING COMMAS TO DOTS
		$text = str_replace(",", ".", $text);
		//CHANGING NO-SIGN MULTIPLICATION e.g. 12(2) to 12*(2); 	
		$regExp = "/(\-?\d+(\.\d+)?)\(/";
		$occurences = preg_match_all($regExp, $text, $matches);
		for($i = 0; $i < $occurences; $i++)
		{
			$replacement = $matches[1][$i] . "*(";
			$text = str_replace($matches[0][$i], $replacement, $text);
		}
		//(2)(4) to (2)*(4)
		$text = str_replace(")(", ")*(", $text);
		
		
		return $text;
	}

	//Calculates the equation passed as parameter
	function calculateEquation($text)
	{
		//DOUBLE OPERATOR HANDLING
		$text = str_replace("--","+",$text);
		$text = str_replace("-+","-",$text);
		$text = str_replace("+-","-",$text);
		$text = str_replace("++","+",$text);
		
		/* ----------------------------------------------------------------------- */
		/* ----------------------------- PARANTHESES ----------------------------- */
		/* ----------------------------------------------------------------------- */
		//Looking for pairs of outermost parantheses
		$paranthesesCounter = 0;
		for($i = 0; $i < strlen($text); $i++)
		{
			if($text[$i] == '(')
			{
				$paranthesesCounter++;
				if(!isset($openingIndex))
				{
					$openingIndex = $i;
				}
			}
			else if($text[$i] == ')')
			{
				$paranthesesCounter--;
				if($paranthesesCounter == 0)
				{
					$closingIndex = $i;
					break;
				}
			}
		}
		//Found a pair, solve what's inside and replace it into original string
		if(isset($openingIndex) and isset($closingIndex))
		{
			$strLength = $closingIndex - $openingIndex;
			$inside = substr($text, $openingIndex+1, $strLength-1);
			$solved = calculateEquation($inside);
			$toReplace = substr($text, $openingIndex, $strLength+1);
			$text = str_replace($toReplace, $solved, $text);
			$text = calculateEquation($text);
		}
			
		/* ----------------------------------------------------------------------- */
		/* ----------------------------- MULTIPLY/DIVIDE ------------------------- */
		/* ----------------------------------------------------------------------- */
		//Checking which equation, multiplying or dividing, occurs first, or if they occur at all
		if(preg_match("/(-?\d+(\.\d+)?)\*(-?\d+(\.\d+)?)/", $text, $matches, PREG_OFFSET_CAPTURE))
		{
			$multiplyIndex = $matches[0][1];
		}
		if(preg_match("/(-?\d+(\.\d+)?)\/(-?\d+(\.\d+)?)/", $text, $matches, PREG_OFFSET_CAPTURE))
		{
			$divideIndex = $matches[0][1];
		}
		$multiplyFirst = false;

		//Both occur
		if(isset($multiplyIndex) and isset($divideIndex))
		{
			//Comparing which is first
			if($multiplyIndex < $divideIndex)
			{
				$multiplyFirst = true;
			}
		}
		//Only one occurs, checking which one
		else if (isset($multiplyIndex))
		{
			$multiplyFirst = true;
		}

		
		
		//Multiplying or dividing, depending on which equation was first
		if($multiplyFirst == true)
		{
			//Looking for expressions with multiplying
			$regExp = "/(?<!\d)(-?\d+(\.\d+)?)\*(\-?\d+(\.\d+)?)/";
			if(preg_match($regExp, $text, $matchResult))
			{
				$multiplyResult = floatval($matchResult[1]) * floatval($matchResult[3]);
				$text = str_replace($matchResult[0], $multiplyResult, $text);
				$text = calculateEquation($text);
			}
		}
		else
		{
			//Looking for expressions with division
			$regExp = "/(?<!\d)(-?\d+(\.\d+)?)\/(\-?\d+(\.\d+)?)/";
			if(preg_match($regExp, $text, $matchResult))
			{
				//Checking for division by zero
				if(floatval($matchResult[3]) != 0)
				{
					$divideResult = floatval($matchResult[1]) / floatval($matchResult[3]);
					$text = str_replace($matchResult[0], $divideResult, $text);
					$text = calculateEquation($text);
				}
				else
				{
					$text = "Error: Division by zero!";
				}
			}
		}
			
			
		/* -------------------------------------------------------------------- */
		/* ----------------------------- ADD/SUBTRACT ------------------------- */
		/* -------------------------------------------------------------------- */
		//Checking which equation, adding or subtracting, occurs first, or if they occur at all
		if(preg_match("/(-?\d+(\.\d+)?)\+(-?\d+(\.\d+)?)/", $text, $matches, PREG_OFFSET_CAPTURE))
		{
			$addIndex = $matches[0][1];
		}
		if(preg_match("/(-?\d+(\.\d+)?)-(-?\d+(\.\d+)?)/", $text, $matches, PREG_OFFSET_CAPTURE))
		{
			$subtractIndex = $matches[0][1];
		}
		$addFirst = false;
		
		//Both occur
		if(isset($addIndex) and isset($subtractIndex))
		{
			//Comparing which is first
			if($addIndex < $subtractIndex)
			{
				$addFirst = true;
			}
		}
		//Only one occurs, checking which one
		else if (isset($addIndex))
		{
			$addFirst = true;
		}
		
		
			
		//Adding or subtracting, depending on which sign was first
		if($addFirst == true)
		{
			//Looking for expressions with addition
			$regExp = "/(-?\d+(\.\d+)?)\+(-?\d+(\.\d+)?)/";
			if(preg_match($regExp, $text, $matchResult))
			{
				$addResult = floatval($matchResult[1]) + floatval($matchResult[3]);
				$text = str_replace($matchResult[0], $addResult, $text);
				$text = calculateEquation($text);
			}
		}
		else
		{
			//Looking for expressions with subtraction
			$regExp = "/(-?\d+(\.\d+)?)-(-?\d+(\.\d+)?)/";
			if(preg_match($regExp, $text, $matchResult))
			{
				$subtractResult = floatval($matchResult[1]) - floatval($matchResult[3]);
				$text = str_replace($matchResult[0], $subtractResult, $text);
				$text = calculateEquation($text);
			}
		}
		
		
		
		//CHECKING IF NUMBER ISN'T TOO HIGH
		if(preg_match("/E/", $text, $matchResult))
		{
			$text = "<Strong>Error</strong>: Number too high.";
		}
			
		return $text;
	}
?>



<HTML>
	<Head>
		<Meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<Title>Calculator</Title>
		<Style>
			body 		{background: linear-gradient(beige,burlywood);		
						font-family: Arial, Helvetica, sans-serif;}
			#title 		{text-align: center;		
						font-size: 20px;}
			#subtitle 	{text-align: center;}
			#form 		{text-align: center;	
						padding-top: 10px;}
			input 		{-webkit-box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.75);
						-moz-box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.75);
						box-shadow: 2px 2px 1px 0px rgba(0,0,0,0.75);}
		</Style>
	</Head>

	
	
	<Body>
	
		<Div id='title'>String &rarr; Float Calculator using RegEx</Div>
		<Div id='subtitle'>Implemented operators: <Strong>( ) + - * / , .</Strong></Div>
		<Div id='form'>
			<!-- Input fields and submit buttons -->
			<Form method="get" autocomplete="off">
			<Input type="text" name="textInput" id="textInput">
			<Input type="submit">
			</Form>
			
			<!-- Result of the calculation -->
			<?php	
			if(isset($result) and $result!="")
			{
				echo $result;
			}
			?>
		</Div>
		
	</Body>
</HTML>





<script>
	
	document.addEventListener('DOMContentLoaded',function()
    {
        document.getElementById('textInput').focus();
    });

</script>