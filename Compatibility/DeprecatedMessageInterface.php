<?php
/**
 * Copyright (c) 2024 Hawksearch (www.hawksearch.com) - All Rights Reserved
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */
declare(strict_types=1);

namespace HawkSearch\Connector\Compatibility;

/**
 * @internal
 */
interface DeprecatedMessageInterface
{
    /**#@+
     * Deprecated message templates
     */
    const TEMPLATE_SINCE_PART = 'Since %s:';
    const TEMPLATE_PUBLIC_METHOD_MAIN_PART = 'Method %s has been deprecated and it\'s public usage will be discontinued.';
    const TEMPLATE_DERIVED_METHOD_MAIN_PART = 'Inheritance of %s method is deprecated because of deprecation of the base method.';
    const TEMPLATE_NEW_METHOD_MAIN_PART = 'Class %s is deprecated without %s method.';
    const TEMPLATE_INTERFACE_MAIN_PART = 'Interface %s has been deprecated and will be removed.';
    const TEMPLATE_CLASS_MAIN_PART = 'Class %s has been deprecated and will be removed.';
    const TEMPLATE_PARAMETER_MAIN_PART = 'Parameter %s has been deprecated in method %s and will be removed.';
    const TEMPLATE_REPLACEMENT_PART = 'Use %s instead.';
    /**#@-*/

}
