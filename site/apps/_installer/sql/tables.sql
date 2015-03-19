SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) NOT NULL,
  `user` varchar(250) CHARACTER SET latin1 NOT NULL,
  `pwd` varchar(250) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `message` mediumtext NOT NULL,
  `info` varchar(250) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=135 ;

INSERT INTO `blog` (`id`, `title`, `message`, `info`) VALUES
(1, 'Welcome', '> Balero CMS is free and Open Source', 'demo @ 2013-11-28 06:27:09'),
(2, 'Markdown', '# This is a h1 header\r\n\r\n## This is a h2 header\r\n\r\n### This is a h3 header', 'demo @ 2013-11-28 06:28:31'),
(3, 'Admin Panel', '<a href="./admin">Administrator Panel</a>', 'demo @ 2013-11-28 06:29:23'),
(4, 'Example news', 'Examples news Examples news Examples news Examples news\r\nExamples news Examples news Examples news Examples news\r\nExamples news Examples news Examples news Examples news', 'demo @ 2013-11-28 06:30:20');

CREATE TABLE IF NOT EXISTS `blog_multilang` (
  `post_id` varchar(10) NOT NULL,
  `title` varchar(250) NOT NULL,
  `message` mediumtext NOT NULL,
  `info` varchar(250) NOT NULL,
  `code` varchar(50) NOT NULL,
  `id` int(10) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `box_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `cookie` (
  `name` varchar(250) NOT NULL,
  `value` varchar(250) NOT NULL,
  `expire` varchar(250) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `custom_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) CHARACTER SET latin1 NOT NULL,
  `url_friendly` int(10) NOT NULL,
  `pagination` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `custom_settings` (`id`, `theme`, `url_friendly`, `pagination`) VALUES
(1, 'tundra', 1, 5);

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(10) NOT NULL,
  `text` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) CHARACTER SET latin1 NOT NULL,
  `password` int(250) NOT NULL,
  `genre` varchar(250) CHARACTER SET latin1 NOT NULL,
  `avatar` mediumblob NOT NULL,
  `avatar_type` varchar(30) CHARACTER SET latin1 NOT NULL,
  `country` varchar(250) CHARACTER SET latin1 NOT NULL,
  `newsletter` varchar(10) CHARACTER SET latin1 NOT NULL,
  `url` varchar(250) CHARACTER SET latin1 NOT NULL,
  `250` varchar(250) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `virtual_page` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `virtual_title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `virtual_content` mediumtext NOT NULL,
  `date` varchar(250) CHARACTER SET latin1 NOT NULL,
  `active` int(10) NOT NULL,
  `visible` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

INSERT INTO `virtual_page` (`id`, `virtual_title`, `virtual_content`, `date`, `active`, `visible`) VALUES
(1, 'License', '> The GNU General Public License is a free, copyleft license for software and other kinds of works.\r\n\r\n> The licenses for most software and other practical works are designed to take away your freedom to share and change the works. By contrast, the GNU General Public License is intended to guarantee your freedom to share and change all versions of a program--to make sure it remains free software for all its users. We, the Free Software Foundation, use the GNU General Public License for most of our software; it applies also to any other work released this way by its authors. You can apply it to your programs, too.\r\n\r\n> When we speak of free software, we are referring to freedom, not price. Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for them if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs, and that you know you can do these things.\r\n\r\n> To protect your rights, we need to prevent others from denying you these rights or asking you to surrender the rights. Therefore, you have certain responsibilities if you distribute copies of the software, or if you modify it: responsibilities to respect the freedom of others.\r\n\r\n> For example, if you distribute copies of such a program, whether gratis or for a fee, you must pass on to the recipients the same freedoms that you received. You must make sure that they, too, receive or can get the source code. And you must show them these terms so they know their rights.\r\n\r\n> Developers that use the GNU GPL protect your rights with two steps: (1) assert copyright on the software, and (2) offer you this License giving you legal permission to copy, distribute and/or modify it.\r\n\r\n> For the developers'' and authors'' protection, the GPL clearly explains that there is no warranty for this free software. For both users'' and authors'' sake, the GPL requires that modified versions be marked as changed, so that their problems will not be attributed erroneously to authors of previous versions.\r\n\r\n> Some devices are designed to deny users access to install or run modified versions of the software inside them, although the manufacturer can do so. This is fundamentally incompatible with the aim of protecting users'' freedom to change the software. The systematic pattern of such abuse occurs in the area of products for individuals to use, which is precisely where it is most unacceptable. Therefore, we have designed this version of the GPL to prohibit the practice for those products. If such problems arise substantially in other domains, we stand ready to extend this provision to those domains in future versions of the GPL, as needed to protect the freedom of users.\r\n\r\n> Finally, every program is threatened constantly by software patents. States should not allow patents to restrict development and use of software on general-purpose computers, but in those that do, we wish to avoid the special danger that patents applied to a free program could make it effectively proprietary. To prevent this, the GPL assures that patents cannot be used to render the program non-free.\r\n\r\n> The precise terms and conditions for copying, distribution and modification follow.\r\n', '2013-07-04', 1, 1);

CREATE TABLE IF NOT EXISTS `virtual_page_multilang` (
  `page_id` varchar(10) NOT NULL,
  `virtual_title` varchar(250) NOT NULL,
  `virtual_content` mediumtext NOT NULL,
  `date` varchar(250) NOT NULL,
  `active` int(10) NOT NULL,
  `visible` int(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `id` int(10) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
