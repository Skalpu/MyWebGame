//LOOKING FOR ILLEGAL CHARACTERS
		$regExp = "/[^0-9|\(|\)|\*|\/|\+|\-]/";
		preg_match($regExp, $text, $matchResult);
		if(count($matchResult) > 0)
		{
			$text = "Error: illegal characters found!";
		}
		
		else
		{
			//LOOKING FOR EXPRESSIONS IN PARANTHESES
			$regExp = "/(?<=\()[^\)]+/";
			preg_match($regExp, $text, $matchResult);
			if(count($matchResult) > 0)
			{
				$inside = $matchResult[0];
				$inside = processEquation($inside);
				$text = str_replace($matchResult[0], $inside, $text);
			}
			//LOOKING FOR "SOLVED" PARANTHESES
			$regExp = "/\(\d*\)/";
			preg_match($regExp, $text, $matchResult);
			if(count($matchResult) > 0)
			{
				$regExp = "/\d+/";
				preg_match($regExp, $matchResult[0], $extracted);
				$text = str_replace($matchResult[0], $extracted[0], $text);
			}
		
			//LOOKING FOR EXPRESSIONS WITH MULTIPLYING
			$regExp = "/\d*\*\d*/";
			preg_match($regExp, $text, $matchResult);
			if(count($matchResult) > 0)
			{
				//Anything before *
				$regExp = "/[^\*]*/";
				preg_match($regExp, $matchResult[0], $valueA);
				//Anything after *
				$regExp = "/(?<=\*).*/";
				preg_match($regExp, $matchResult[0], $valueB);
			
				$multiplyResult = intval($valueA[0]) * intval($valueB[0]);
				$text = str_replace($matchResult[0], $multiplyResult, $text);
				$text = processEquation($text);
			}
		
			//LOOKING FOR EXPRESSIONS WITH DIVISION
			$regExp = "/\d*\/\d*/";
			preg_match($regExp, $text, $matchResult);
			if(count($matchResult) > 0)
			{
				//Anything before /
				$regExp = "/[^\/]*/";
				preg_match($regExp, $matchResult[0], $valueA);
				//Anything after /
				$regExp = "/(?<=\/).*/";
				preg_match($regExp, $matchResult[0], $valueB);
			
				if($valueB[0] == '0')
				{
					$text = "Error: Division by zero!";
				}
				else
				{
					$divisionResult = intval($valueA[0]) / intval($valueB[0]);
					$text = str_replace($matchResult[0], $divisionResult, $text);
					$text = processEquation($text);
				}
			}
		
			//LOOKING FOR EXPRESSIONS WITH ADDITION
			$regExp = "/\d*\+\d*/";
			preg_match($regExp, $text, $matchResult);
			if(count($matchResult) > 0)
			{
				//Anything before +
				$regExp = "/[^\+]*/";
				preg_match($regExp, $matchResult[0], $valueA);
				//Anything after +
				$regExp = "/(?<=\+).*/";
				preg_match($regExp, $matchResult[0], $valueB);
			
				$additionResult = intval($valueA[0]) + intval($valueB[0]);
				$text = str_replace($matchResult[0], $additionResult, $text);
				$text = processEquation($text);
			}
		
		
			//LOOKING FOR EXPRESSIONS WITH SUBSTRACTION
			$regExp = "/\d*\-\d*/";
			preg_match($regExp, $text, $matchResult);
			//FOUND AT LEAST ONE EXPRESSION
			if(count($matchResult) > 0)
			{
				//Anything before -
				$regExp = "/[^\-]*/";
				preg_match($regExp, $matchResult[0], $valueA);
				//Anything after -
				$regExp = "/(?<=\-).*/";
				preg_match($regExp, $matchResult[0], $valueB);
			
				$additionResult = intval($valueA[0]) - intval($valueB[0]);
				$text = str_replace($matchResult[0], $additionResult, $text);
				$text = processEquation($text);
			}
		}
		
		return $text;