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

use HawkSearch\Connector\Compatibility\PublicPropertyDeprecationTrait;

class PublicPropertyDeprecationBase
{
    use PublicPropertyDeprecationTrait;

    private array $deprecatedPublicProperties = [];

    /**
     * @deprecated
     */
    private string $propertyDeprecatedChangedToPrivateScope = 'initial private value';

    /**
     * @deprecated
     */
    protected string $propertyDeprecatedChangedToProtectedScope = 'initial protected value';

    protected string $regularProtectedProperty = 'regular';

    private string $regularPrivateProperty = 'regular';

    public function __construct(array $deprecatedPublicProperties)
    {
        $this->deprecatedPublicProperties = $deprecatedPublicProperties;
    }

    public function accessDeprecatedPrivateFromPublicMethodNoDeprecationNotice(): string
    {
        return $this->propertyDeprecatedChangedToPrivateScope;
    }

    public function accessDeprecatedProtectedFromPublicMethodNoDeprecationNotice(): string
    {
        return $this->propertyDeprecatedChangedToProtectedScope;
    }
}

class PublicPropertyDeprecationDerived extends PublicPropertyDeprecationBase
{
    public function accessRegularProtectedPropertyFromDerivedClassNoDeprecationNotice(): string
    {
        return $this->regularProtectedProperty;
    }

    public function accessDeprecatedPrivatePropertyFromDerivedClass(): string
    {
        $property = 'propertyDeprecatedChangedToPrivateScope';
        return $this->$property;
    }

    public function accessDeprecatedProtectedPropertyFromDerivedClassNoDeprecationNotice(): string
    {
        return $this->propertyDeprecatedChangedToProtectedScope;
    }

    public function setProperty(string $property, mixed $value): void
    {
        $this->$property = $value;
    }
}
