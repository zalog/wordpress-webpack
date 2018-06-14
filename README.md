# WordPress Webpack

Get Webpack into your WordPress workflow. And other stuff like: vagrant, BrowserSync, gulp, critical css, latest bootstrap, photoswipe, etc.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- [git](https://git-scm.com/)
- [vagrant](https://www.vagrantup.com/) and [virtualbox](https://www.virtualbox.org/)
- [EditorConfig](https://editorconfig.org/) for using the same indenting rules

#### Exposed vagrant ports

In the creating/configuring process of your virtual machine, vagrant will check if this default ports, on your host machine, are free: 2222(ssh), 8080(apache), 3000(BrowserSync) or 3001(BrowserSync), if not will change that accordingly.
To see what ports are exposed to you, run `vagrant port`.
In the rest of the readme file we will use the default ports.

### Installing

- `git clone` this repo
- run `vagrant up`, this get the virtual machine up and running
- go to [localhost:8080/wp-admin/](http://localhost:8080/wp-admin/)
  - u: admin p: admin
  - and visit [Settings -> Permalinks](http://localhost:8080/wp-admin/options-permalink.php) for the .htaccess file to be generated
- go to [localhost:8080/](http://localhost:8080/) here you can view the theme

### Running nodejs gulp tasks

All the action happens in the vagrant vm, so you'll have to ssh that.
The build gulp tasks are not to generating anything on the host machine under our repo working space, to keep our IDEs, git and focus, clear from some unwanted generated files.

- `vagrant ssh` ssh to vagrant vm. You can also acces that with WinSCP u: vagrant, p: vagrant, port: 2222

  - run `npm run start` for the default task in gulp that is a develompent mode list of tasks ('dev', 'serve' and 'watch'). The tasks from here are written with speed in mind, so in this pipes there will be not tasks that take time like uglify/minify, autoprefixer and so on.
    - go to [localhost:3000](http://localhost:3000/) for BrowserSync

  - run `npm run build` preparing the theme for production

  - run `npm run serve` simply just serving the files compiled trow BrowserSync [localhost:3000](http://localhost:3000/). Please do not forget that in this task any change you make on the `/src` will not be propagated to our vagrant vm.

---

As long as vagrant is up, you can always access [localhost:8080](http://localhost:8080/).
As long as in the gulp pipes, `serve` task is runnging, you can always access BrowserSync [localhost:3000](http://localhost:3000/) and [localhost:3001](http://localhost:3001/).
As long as in the gulp pipes, `watch` task is runnging, you can always see you changes from `/src` to [localhost:8080](http://localhost:8080/) or [localhost:3000](http://localhost:3000/).

So please keep in mind that the files from `/src` are compiled&copied only if you run one of:

- `npm run start`
- `npm run build`
- `gulp watch`

## Built With

There are a lot of hours given to the community, thanks guys, keep up the good work:

- [wordpress](https://wordpress.org/)
- [bootstrap](http://getbootstrap.com/)
- [BrowserSync](https://github.com/BrowserSync/browser-sync)
- [gulp](https://github.com/gulpjs/gulp)
- [critical](https://github.com/addyosmani/critical)
- [PhotoSwipe](https://github.com/dimsemenov/PhotoSwipe)
- and all that stuff that make our life easier

## Contributing

Here are the some angular's [CONTRIBUTING.md](https://github.com/angular/angular/blob/master/CONTRIBUTING.md) guidelines.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the tags on this repo.

## Authors

- **[zalog](http://zalog.ro)**, initial work, front-end, build