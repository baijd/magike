# Magike在dreamhost上的问题 #

虽然Magike可以支持所有的PHP５平台，但是由于PHP本身的一些Bug和服务器的配置问题，可能会导致运行上的错误。

由于PHP 5.2的bug，无法在PHP内部取到全局变量HTTP\_RAW\_POST\_DATA，这个bug会影响XML-RPC API函数，具体表现为系统提示无法取得POST REQUEST。这个BUG我们已经在SVN Build 265版本后修复，不会影响正常使用。

另一个错误是Dreamhost默认打开了php的short\_tag选项，这个选项允许你使用<?而不是<?php来开始一段php程序，这个错误会影响xml文档的输出，比如RSS。
为了修复这个错误，您可以选择自己重装PHP，dreamhost允许用户自己重装PHP。然后再php.ini里面关掉short\_tag。