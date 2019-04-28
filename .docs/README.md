## Setup

Install package

```bash
composer require tlapnet/settus
```

Register DI extension

```yaml
extensions:
    settus: Tlapnet\Settus\DI\SettusExtension
```

## Configuration

Add sections. Every section needs `Storage` and `template`. 

```yaml
settus:
    sections: 
        application:
            storage: Tlapnet\Settus\Storage\NeonReadOnlyStorage(%settings.core%)
            template:
                host:
                    description: Host stored in neon
                    default: null
                
                database:
                    description: Database name in neon
                    default: settus
        user:
            storage: @settings.doctrineStorage
            template:
                user:
                    description: User stored in db
                    default: root
                
                password:
                    description: Password stored in db
                    default: null
```

Template can be also provided by configurator.

```yaml
settus:
    sections: 
        application:
            storage: Tlapnet\Settus\Storage\NeonReadOnlyStorage(%settings.core%)
            configurator: Tlapnet\Settus\Configurator\NeonConfigurator(%settings.core%)
```


## Usage

## Template 

Required fields are `description` and `default` value.

```yaml
host:
    description: Host
    default: null

database:
    description: Database
    default: null

user:
    description: Database
    default: root

password:
    description: Database
    default: null
```

Optionally you can set `type`, `control`, `hidden`.

```yaml
password:
    description: List
    default: a
    type: string
    hidden: true
    control:
        type: select
        items:
            a: A
            b: B
            c: C
```

### Control

You can change control type - possible types are `text`, `password`, `checkbox`, `select`.

## Configurator

Optionally.

### NeonConfigurator

Takes parameter section from neon.

```yaml
configurator: Tlapnet\Settus\Configurator\NeonConfigurator(%settings.core%)
```
### Custom Configurator

Implements `Tlapnet\Settus\Configurator\IConfigurator` and build array of `Setting`.

## Storage

Required.

### NeonReadOnlyStorage

Takes parameter section from neon.

```yaml
storage: Tlapnet\Settus\Storage\NeonReadOnlyStorage(%settings%)
```

Load values.

```yaml
parameters:
   settings:
      host: localhost
      value: project
      user: root
```

### Custom Storage

`TODO` 

## SettingsManager

Service registered in DIC. 

`TODO` 

## Section

`TODO` 

## SettingsControl

Create.

```php
/** @var ISettingsControlFactory @inject */
public $settingsControlFactory;

/** @var SettingsManager @inject */
public $settingsManager;

protected function createComponentSettings(): SettingsControl
{
    $section = $this->settingsManager->getSection('user');
    return $this->settingsControlFactory->create($section);
}
```

Optionally enable `read-only` mode.

```php
$component->setReadOnly(TRUE);
```

Optionally enable hide `default` value.

```php
$component->setShowDefault(FALSE);
```

