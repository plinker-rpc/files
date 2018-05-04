# PlinkerRPC - Files

A files component which allows you to read and write files.

## Install

Require this package with composer using the following command:

``` bash
$ composer require plinker/files
```

## Client

Creating a client instance is done as follows:


    <?php
    require 'vendor/autoload.php';

    /**
     * Initialize plinker client.
     *
     * @param string $server - URL to server listener.
     * @param string $config - server secret, and/or a additional component data
     */
    $client = new \Plinker\Core\Client(
        'http://example.com/server.php',
        [
            'secret' => 'a secret password'
        ]
    );
    
    // or using global function, with optional array
    $client = plinker_client('http://example.com/server.php', 'a secret password');
    

## Methods

Once setup, you call the class though its namespace to its method.

### User

Get current user, helps to debug which user the crontab is owned by.

**Call**
``` php
$result = $client->cron->user();
```

**Response**
``` text
www-data
```

### Crontab

Get current crontab, equivalent to `crontab -l`.

**Call**
``` php
$result = $client->cron->crontab();
```

**Response**
``` text
# My Cron Task
0 * * * * cd ~
# \My Cron Task
```

### Dump

Get current crontab journal. The journal is a file which gets built and then applied to the real crontab.

**Call**
``` php
$result = $client->cron->dump();
```

**Response**
``` text
# My Cron Task
0 * * * * cd ~
# \My Cron Task
```

### Create

Create a crontask entry. Note one entry per key, multiple calls with same key would simply update.

**Call**
``` php
$result = $client->cron->create('My Cron Task', '* * * * * cd ~');
```

**Response**
``` text

```

### Get

Get a crontask entry, also has an alias method read.

**Call**
``` php
$result = $client->cron->get('My Cron Task');
```

**Response**
``` text
0 * * * * cd ~
```

### Update

Update cron task.

**Call**
``` php
$result = $client->cron->update('My Cron Task', '0 * * * * cd ~');
```

**Response**
``` text

```

### Delete

Delete a cron task.

**Call**
``` php
$result = $client->cron->delete('My Cron Task');
```

**Response**
``` text

```

### Drop

Drop cron task journal (delete all, but does not apply it).

**Call**
``` php
$result =  $client->cron->drop();
```

**Response**
``` text

```

### Apply

Apply crontab journal to users crontab.

**Call**
``` php
$result = $client->cron->apply();
```

**Response**
``` text

```

## Testing

There are no tests setup for this component.

## Contributing

Please see [CONTRIBUTING](https://github.com/plinker-rpc/files/blob/master/CONTRIBUTING) for details.

## Security

If you discover any security related issues, please contact me via [https://cherone.co.uk](https://cherone.co.uk) instead of using the issue tracker.

## Credits

- [Lawrence Cherone](https://github.com/lcherone)
- [All Contributors](https://github.com/plinker-rpc/files/graphs/contributors)


## Development Encouragement

If you use this project and make money from it or want to show your appreciation,
please feel free to make a donation [https://www.paypal.me/lcherone](https://www.paypal.me/lcherone), thanks.

## Sponsors

Get your company or name listed throughout the documentation and on each github repository, contact me at [https://cherone.co.uk](https://cherone.co.uk) for further details.

## License

The MIT License (MIT). Please see [License File](https://github.com/plinker-rpc/files/blob/master/LICENSE) for more information.

See the [organisations page](https://github.com/plinker-rpc) for additional components.














PlinkerRPC PHP client/server makes it really easy to link and execute PHP component classes on remote systems, while maintaining the feel of a local method call.

A files component which allows you to read and write files.

**Composer**

    {
    	"require": {
    		"plinker/files": ">=v0.1"
    	}
    }



Making remote calls.
--------------------

    <?php
    require '../../vendor/autoload.php';
    
    try {
        
        /**
         * Plinker Config
         */
        $config = [
            // plinker connection | using tasks as to write in the correct .sqlite file
            'plinker' => [
                'endpoint' => 'http://127.0.0.1/examples/cron/server.php',
                'public_key'  => 'makeSomethingUp',
                'private_key' => 'againMakeSomethingUp'
            ],
        
            // optional config
            'config' => [
                'journal' => './crontab.journal',
                'apply'   => false
            ]
        ];
        
        // init plinker endpoint client
        $cron = new \Plinker\Core\Client(
            // where is the plinker server
            $config['plinker']['endpoint'],
        
            // component namespace to interface to
            'Files\Manager',
        
            // keys
            $config['plinker']['public_key'],
            $config['plinker']['private_key'],
        
            // construct array which you pass to the component
            $config['config']
        );
    
        // todo! 
        
    } catch (\Exception $e) {
        exit(get_class($e).': '.$e->getMessage());
    }

**then the server part...**

    <?php
    require '../../vendor/autoload.php';
    
    /**
     * Its POST..
     */
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        /**
         * Its Plinker!
         */
        if (isset($_SERVER['HTTP_PLINKER'])) {
            // test its encrypted
            file_put_contents('./.plinker/encryption-proof.txt', print_r($_POST, true));
    
            /**
             * Define Plinker Config
             */
            $plinker = [
                'public_key'  => 'makeSomethingUp',
                'private_key' => 'againMakeSomethingUp',
                // optional config
                /*'config' => [
                    // allowed ips, restrict access by ip
                    'allowed_ips' => [
                        '127.0.0.1'
                    ]
                ]*/
            ];
    
            // init plinker server
            $server = new \Plinker\Core\Server(
                $_POST,
                $plinker['public_key'],
                $plinker['private_key'],
                (array) @$plinker['config']
            );
    
            exit($server->execute());
        }
    }


See the [organisations page](https://github.com/plinker-rpc) for additional components.