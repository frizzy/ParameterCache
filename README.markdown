# Parameter Cache

Manages caching for a parameter array to a PHP file

## Usage

Add the following to your root composer.json file:

    {
        "require": {
            "frizzy/parameter-cache": "0.*"
        }
    }

### Load a YAML parameter file:

parameters.yml:

    parameters:
        project.name: 'My Project'


Load content into array:
    
    <?php
    
    $loader = new \Frizzy\ParameterCache\ClosureLoader(
        function ($file) {
            $data = \Symfony\Component\Yaml\Yaml::parse($file);
            
            return $data['parameters'];
        },
        __DIR__ . '/cache'
    );
    $parameters = array(
        'project.name' => 'Default project name'
    );
    $loader->load(__DIR__ . '/parameters.yml', $parameters);
    
    ?>