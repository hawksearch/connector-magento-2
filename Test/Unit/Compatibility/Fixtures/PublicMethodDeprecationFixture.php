<?php
/**
 * Copyright (c) 2025 Hawksearch (www.hawksearch.com) - All Rights Reserved
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

namespace HawkSearch\Connector\Test\Unit\Compatibility\Fixtures;

use HawkSearch\Connector\Compatibility\PublicMethodDeprecationTrait;

class PublicMethodDeprecationBase
{
    use PublicMethodDeprecationTrait;

    private array $deprecatedMethods = [];

    public function __construct(array $deprecatedMethods)
    {
        $this->deprecatedMethods = $deprecatedMethods;
    }

    private function doSomeDeprecatedActionChangedVisibilityToPrivate(string $argument1): string
    {
        return $argument1 . ' is called';
    }

    protected function doSomeDeprecatedActionChangedVisibilityToProtected(string $argument1): string
    {
        return $argument1 . ' is called';
    }

    public function callDeprecatedPrivateFromPublicMethod(): string
    {
        return $this->doSomeDeprecatedActionChangedVisibilityToPrivate(__FUNCTION__);
    }

    public function doRegularPublicAction(): void
    {

    }

    public function doPublicJustOnlyAnnotatedAction(): void
    {

    }

    public function doPublicTriggeredNotAnnotatedAction(): void
    {
        $this->triggerPublicMethodDeprecationMessage(__FUNCTION__);
    }

    public function doPublicTriggeredAndAnnotatedAction(): void
    {
        $this->triggerPublicMethodDeprecationMessage(__FUNCTION__);
    }

    private function doPrivateAction(): void
    {

    }

    protected function doProtectedAction(): void
    {

    }

    private function doOverwrittenDeprecatedAction(string $arg): string
    {
        return $arg;
    }

    public function callDerivedDeprecatedAction(): string
    {
        $this->triggerDerivedMethodDeprecationMessage('doOverwrittenDeprecatedAction');
        if ($this->isMethodOverwritten('doOverwrittenDeprecatedAction')) {
            return $this->doOverwrittenDeprecatedAction('From collectDerivedActions method');
        } else {
            return 'not overwritten!';
        }
    }

    public function callDerivedActions(): void
    {
        $this->triggerDerivedMethodDeprecationMessage('doSomeDeprecatedActionChangedVisibilityToProtected');
    }

}

class PublicMethodDeprecationDerived extends PublicMethodDeprecationBase
{
    public function callDeprecatedPrivateMethodFromDerivedClass(): string
    {
        return $this->doSomeDeprecatedActionChangedVisibilityToPrivate(__FUNCTION__);
    }

    public function callDeprecatedProtectedMethodFromDerivedClass(): string
    {
        return $this->doSomeDeprecatedActionChangedVisibilityToProtected(__FUNCTION__);
    }

    public function doOverwrittenDeprecatedAction(string $arg): string
    {
        return parent::doOverwrittenDeprecatedAction($arg) . ' in derived class';
    }
}
