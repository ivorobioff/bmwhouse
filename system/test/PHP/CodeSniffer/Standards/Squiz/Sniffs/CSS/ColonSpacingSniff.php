<?php
/**
 * Squiz_Sniffs_CSS_ColonSpacingSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Squiz_Sniffs_CSS_ColonSpacingSniff.
 *
 * Ensure there is no space before a colon and one space after it.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.6
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Squiz_Sniffs_CSS_ColonSpacingSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('CSS');


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_COLON);

    }//end register()


    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $prev = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($tokens[$prev]['code'] !== T_STYLE) {
            // The colon is not part of a style definition.
            return;
        }

        if ($tokens[($stackPtr - 1)]['code'] === T_WHITESPACE) {
            $error = 'There must be no space before a colon in a style definition';
            $phpcsFile->addError($error, $stackPtr, 'Before');
        }

        if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
            $error = 'Expected 1 space after colon in style definition; 0 found';
            $phpcsFile->addError($error, $stackPtr, 'NoneAfter');
        } else {
            $content = $tokens[($stackPtr + 1)]['content'];
            if (strpos($content, $phpcsFile->eolChar) === false) {
                $length  = strlen($content);
                if ($length !== 1) {
                    $error = 'Expected 1 space after colon in style definition; %s found';
                    $data  = array($length);
                    $phpcsFile->addError($error, $stackPtr, 'After', $data);
                }
            } else {
                $error = 'Expected 1 space after colon in style definition; newline found';
                $phpcsFile->addError($error, $stackPtr, 'AfterNewline');
            }
        }//end if

    }//end process()

}//end class
?>