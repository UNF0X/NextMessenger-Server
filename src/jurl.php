<?php
/**
 * Включает поддержку синтаксиса cURL, как в обычном PHP
 * становятся доступными curl_* функции
 *
 * Обязательно, чтоб функции были объявлены в корневом namespace
 */

namespace;

use php\lib\Str,
    Addons\jURL,
    Addons\jURL\jURLFile,
    Addons\jURL\jURLException;

// Импорт define из стандартного curl
define("CURLOPT_AUTOREFERER", 58);
define("CURLOPT_BINARYTRANSFER", 19914);
define("CURLOPT_BUFFERSIZE", 98);
define("CURLOPT_CAINFO", 10065);
define("CURLOPT_CAPATH", 10097);
define("CURLOPT_CONNECTTIMEOUT", 78);
define("CURLOPT_COOKIE", 10022);
define("CURLOPT_COOKIEFILE", 10031);
define("CURLOPT_COOKIEJAR", 10082);
define("CURLOPT_COOKIESESSION", 96);
define("CURLOPT_CRLF", 27);
define("CURLOPT_CUSTOMREQUEST", 10036);
define("CURLOPT_DNS_CACHE_TIMEOUT", 92);
define("CURLOPT_DNS_USE_GLOBAL_CACHE", 91);
define("CURLOPT_EGDSOCKET", 10077);
define("CURLOPT_ENCODING", 10102);
define("CURLOPT_FAILONERROR", 45);
define("CURLOPT_FILE", 10001);
define("CURLOPT_FILETIME", 69);
define("CURLOPT_FOLLOWLOCATION", 52);
define("CURLOPT_FORBID_REUSE", 75);
define("CURLOPT_FRESH_CONNECT", 74);
define("CURLOPT_FTPAPPEND", 50);
define("CURLOPT_FTPLISTONLY", 48);
define("CURLOPT_FTPPORT", 10017);
define("CURLOPT_FTP_USE_EPRT", 106);
define("CURLOPT_FTP_USE_EPSV", 85);
define("CURLOPT_HEADER", 42);
define("CURLOPT_HEADERFUNCTION", 20079);
define("CURLOPT_HTTP200ALIASES", 10104);
define("CURLOPT_HTTPGET", 80);
define("CURLOPT_HTTPHEADER", 10023);
define("CURLOPT_HTTPPROXYTUNNEL", 61);
define("CURLOPT_HTTP_VERSION", 84);
define("CURLOPT_INFILE", 10009);
define("CURLOPT_INFILESIZE", 14);
define("CURLOPT_INTERFACE", 10062);
define("CURLOPT_KRB4LEVEL", 10063);
define("CURLOPT_LOW_SPEED_LIMIT", 19);
define("CURLOPT_LOW_SPEED_TIME", 20);
define("CURLOPT_MAXCONNECTS", 71);
define("CURLOPT_MAXREDIRS", 68);
define("CURLOPT_NETRC", 51);
define("CURLOPT_NOBODY", 44);
define("CURLOPT_NOPROGRESS", 43);
define("CURLOPT_NOSIGNAL", 99);
define("CURLOPT_PORT", 3);
define("CURLOPT_POST", 47);
define("CURLOPT_POSTFIELDS", 10015);
define("CURLOPT_POSTQUOTE", 10039);
define("CURLOPT_PREQUOTE", 10093);
define("CURLOPT_PRIVATE", 10103);
define("CURLOPT_PROGRESSFUNCTION", 20056);
define("CURLOPT_PROXY", 10004);
define("CURLOPT_PROXYPORT", 59);
define("CURLOPT_PROXYTYPE", 101);
define("CURLOPT_PROXYUSERPWD", 10006);
define("CURLOPT_PUT", 54);
define("CURLOPT_QUOTE", 10028);
define("CURLOPT_RANDOM_FILE", 10076);
define("CURLOPT_RANGE", 10007);
define("CURLOPT_READDATA", 10009);
define("CURLOPT_READFUNCTION", 20012);
define("CURLOPT_REFERER", 10016);
define("CURLOPT_RESUME_FROM", 21);
define("CURLOPT_RETURNTRANSFER", 19913);
define("CURLOPT_SHARE", 10100);
define("CURLOPT_SSLCERT", 10025);
define("CURLOPT_SSLCERTPASSWD", 10026);
define("CURLOPT_SSLCERTTYPE", 10086);
define("CURLOPT_SSLENGINE", 10089);
define("CURLOPT_SSLENGINE_DEFAULT", 90);
define("CURLOPT_SSLKEY", 10087);
define("CURLOPT_SSLKEYPASSWD", 10026);
define("CURLOPT_SSLKEYTYPE", 10088);
define("CURLOPT_SSLVERSION", 32);
define("CURLOPT_SSL_CIPHER_LIST", 10083);
define("CURLOPT_SSL_VERIFYHOST", 81);
define("CURLOPT_SSL_VERIFYPEER", 64);
define("CURLOPT_STDERR", 10037);
define("CURLOPT_TELNETOPTIONS", 10070);
define("CURLOPT_TIMECONDITION", 33);
define("CURLOPT_TIMEOUT", 13);
define("CURLOPT_TIMEVALUE", 34);
define("CURLOPT_TRANSFERTEXT", 53);
define("CURLOPT_UNRESTRICTED_AUTH", 105);
define("CURLOPT_UPLOAD", 46);
define("CURLOPT_URL", 10002);
define("CURLOPT_USERAGENT", 10018);
define("CURLOPT_USERPWD", 10005);
define("CURLOPT_VERBOSE", 41);
define("CURLOPT_WRITEFUNCTION", 20011);
define("CURLOPT_WRITEHEADER", 10029);
define("CURLE_ABORTED_BY_CALLBACK", 42);
define("CURLE_BAD_CALLING_ORDER", 44);
define("CURLE_BAD_CONTENT_ENCODING", 61);
define("CURLE_BAD_DOWNLOAD_RESUME", 36);
define("CURLE_BAD_FUNCTION_ARGUMENT", 43);
define("CURLE_BAD_PASSWORD_ENTERED", 46);
define("CURLE_COULDNT_CONNECT", 7);
define("CURLE_COULDNT_RESOLVE_HOST", 6);
define("CURLE_COULDNT_RESOLVE_PROXY", 5);
define("CURLE_FAILED_INIT", 2);
define("CURLE_FILE_COULDNT_READ_FILE", 37);
define("CURLE_FTP_ACCESS_DENIED", 9);
define("CURLE_FTP_BAD_DOWNLOAD_RESUME", 36);
define("CURLE_FTP_CANT_GET_HOST", 15);
define("CURLE_FTP_CANT_RECONNECT", 16);
define("CURLE_FTP_COULDNT_GET_SIZE", 32);
define("CURLE_FTP_COULDNT_RETR_FILE", 19);
define("CURLE_FTP_COULDNT_SET_ASCII", 29);
define("CURLE_FTP_COULDNT_SET_BINARY", 17);
define("CURLE_FTP_COULDNT_STOR_FILE", 25);
define("CURLE_FTP_COULDNT_USE_REST", 31);
define("CURLE_FTP_PARTIAL_FILE", 18);
define("CURLE_FTP_PORT_FAILED", 30);
define("CURLE_FTP_QUOTE_ERROR", 21);
define("CURLE_FTP_USER_PASSWORD_INCORRECT", 10);
define("CURLE_FTP_WEIRD_227_FORMAT", 14);
define("CURLE_FTP_WEIRD_PASS_REPLY", 11);
define("CURLE_FTP_WEIRD_PASV_REPLY", 13);
define("CURLE_FTP_WEIRD_SERVER_REPLY", 8);
define("CURLE_FTP_WEIRD_USER_REPLY", 12);
define("CURLE_FTP_WRITE_ERROR", 20);
define("CURLE_FUNCTION_NOT_FOUND", 41);
define("CURLE_GOT_NOTHING", 52);
define("CURLE_HTTP_NOT_FOUND", 22);
define("CURLE_HTTP_PORT_FAILED", 45);
define("CURLE_HTTP_POST_ERROR", 34);
define("CURLE_HTTP_RANGE_ERROR", 33);
define("CURLE_HTTP_RETURNED_ERROR", 22);
define("CURLE_LDAP_CANNOT_BIND", 38);
define("CURLE_LDAP_SEARCH_FAILED", 39);
define("CURLE_LIBRARY_NOT_FOUND", 40);
define("CURLE_MALFORMAT_USER", 24);
define("CURLE_OBSOLETE", 50);
define("CURLE_OK", 0);
define("CURLE_OPERATION_TIMEDOUT", 28);
define("CURLE_OPERATION_TIMEOUTED", 28);
define("CURLE_OUT_OF_MEMORY", 27);
define("CURLE_PARTIAL_FILE", 18);
define("CURLE_READ_ERROR", 26);
define("CURLE_RECV_ERROR", 56);
define("CURLE_SEND_ERROR", 55);
define("CURLE_SHARE_IN_USE", 57);
define("CURLE_SSL_CACERT", 60);
define("CURLE_SSL_CERTPROBLEM", 58);
define("CURLE_SSL_CIPHER", 59);
define("CURLE_SSL_CONNECT_ERROR", 35);
define("CURLE_SSL_ENGINE_NOTFOUND", 53);
define("CURLE_SSL_ENGINE_SETFAILED", 54);
define("CURLE_SSL_PEER_CERTIFICATE", 51);
define("CURLE_SSL_PINNEDPUBKEYNOTMATCH", 90);
define("CURLE_TELNET_OPTION_SYNTAX", 49);
define("CURLE_TOO_MANY_REDIRECTS", 47);
define("CURLE_UNKNOWN_TELNET_OPTION", 48);
define("CURLE_UNSUPPORTED_PROTOCOL", 1);
define("CURLE_URL_MALFORMAT", 3);
define("CURLE_URL_MALFORMAT_USER", 4);
define("CURLE_WRITE_ERROR", 23);
define("CURLINFO_CONNECT_TIME", 3145733);
define("CURLINFO_CONTENT_LENGTH_DOWNLOAD", 3145743);
define("CURLINFO_CONTENT_LENGTH_UPLOAD", 3145744);
define("CURLINFO_CONTENT_TYPE", 1048594);
define("CURLINFO_EFFECTIVE_URL", 1048577);
define("CURLINFO_FILETIME", 2097166);
define("CURLINFO_HEADER_OUT", 2);
define("CURLINFO_HEADER_SIZE", 2097163);
define("CURLINFO_HTTP_CODE", 2097154);
define("CURLINFO_LASTONE", 49);
define("CURLINFO_NAMELOOKUP_TIME", 3145732);
define("CURLINFO_PRETRANSFER_TIME", 3145734);
define("CURLINFO_PRIVATE", 1048597);
define("CURLINFO_REDIRECT_COUNT", 2097172);
define("CURLINFO_REDIRECT_TIME", 3145747);
define("CURLINFO_REQUEST_SIZE", 2097164);
define("CURLINFO_SIZE_DOWNLOAD", 3145736);
define("CURLINFO_SIZE_UPLOAD", 3145735);
define("CURLINFO_SPEED_DOWNLOAD", 3145737);
define("CURLINFO_SPEED_UPLOAD", 3145738);
define("CURLINFO_SSL_VERIFYRESULT", 2097165);
define("CURLINFO_STARTTRANSFER_TIME", 3145745);
define("CURLINFO_TOTAL_TIME", 3145731);
define("CURLMSG_DONE", 1);
define("CURLVERSION_NOW", 3);
define("CURLM_BAD_EASY_HANDLE", 2);
define("CURLM_BAD_HANDLE", 1);
define("CURLM_CALL_MULTI_PERFORM", -1);
define("CURLM_INTERNAL_ERROR", 4);
define("CURLM_OK", 0);
define("CURLM_OUT_OF_MEMORY", 3);
define("CURLM_ADDED_ALREADY", 7);
define("CURLPROXY_HTTP", 0);
define("CURLPROXY_SOCKS4", 4);
define("CURLPROXY_SOCKS5", 5);
define("CURLSHOPT_NONE", 0);
define("CURLSHOPT_SHARE", 1);
define("CURLSHOPT_UNSHARE", 2);
define("CURL_HTTP_VERSION_1_0", 1);
define("CURL_HTTP_VERSION_1_1", 2);
define("CURL_HTTP_VERSION_NONE", 0);
define("CURL_LOCK_DATA_COOKIE", 2);
define("CURL_LOCK_DATA_DNS", 3);
define("CURL_LOCK_DATA_SSL_SESSION", 4);
define("CURL_NETRC_IGNORED", 0);
define("CURL_NETRC_OPTIONAL", 1);
define("CURL_NETRC_REQUIRED", 2);
define("CURL_SSLVERSION_DEFAULT", 0);
define("CURL_SSLVERSION_SSLv2", 2);
define("CURL_SSLVERSION_SSLv3", 3);
define("CURL_SSLVERSION_TLSv1", 1);
define("CURL_TIMECOND_IFMODSINCE", 1);
define("CURL_TIMECOND_IFUNMODSINCE", 2);
define("CURL_TIMECOND_LASTMOD", 3);
define("CURL_TIMECOND_NONE", 0);
define("CURL_VERSION_IPV6", 1);
define("CURL_VERSION_KERBEROS4", 2);
define("CURL_VERSION_LIBZ", 8);
define("CURL_VERSION_SSL", 4);
define("CURLOPT_HTTPAUTH", 107);
define("CURLAUTH_ANY", 4294967279);
define("CURLAUTH_ANYSAFE", 4294967278);
define("CURLAUTH_BASIC", 1);
define("CURLAUTH_DIGEST", 2);
define("CURLAUTH_GSSNEGOTIATE", 4);
define("CURLAUTH_NONE", 0);
define("CURLAUTH_NTLM", 8);
define("CURLINFO_HTTP_CONNECTCODE", 2097174);
define("CURLOPT_FTP_CREATE_MISSING_DIRS", 110);
define("CURLOPT_PROXYAUTH", 111);
define("CURLE_FILESIZE_EXCEEDED", 63);
define("CURLE_LDAP_INVALID_URL", 62);
define("CURLINFO_HTTPAUTH_AVAIL", 2097175);
define("CURLINFO_RESPONSE_CODE", 2097154);
define("CURLINFO_PROXYAUTH_AVAIL", 2097176);
define("CURLOPT_FTP_RESPONSE_TIMEOUT", 112);
define("CURLOPT_IPRESOLVE", 113);
define("CURLOPT_MAXFILESIZE", 114);
define("CURL_IPRESOLVE_V4", 1);
define("CURL_IPRESOLVE_V6", 2);
define("CURL_IPRESOLVE_WHATEVER", 0);
define("CURLE_FTP_SSL_FAILED", 64);
define("CURLFTPSSL_ALL", 3);
define("CURLFTPSSL_CONTROL", 2);
define("CURLFTPSSL_NONE", 0);
define("CURLFTPSSL_TRY", 1);
define("CURLOPT_FTP_SSL", 119);
define("CURLOPT_NETRC_FILE", 10118);
define("CURLFTPAUTH_DEFAULT", 0);
define("CURLFTPAUTH_SSL", 1);
define("CURLFTPAUTH_TLS", 2);
define("CURLOPT_FTPSSLAUTH", 129);
define("CURLOPT_FTP_ACCOUNT", 10134);
define("CURLOPT_TCP_NODELAY", 121);
define("CURLINFO_OS_ERRNO", 2097177);
define("CURLINFO_NUM_CONNECTS", 2097178);
define("CURLINFO_SSL_ENGINES", 4194331);
define("CURLINFO_COOKIELIST", 4194332);
define("CURLOPT_COOKIELIST", 10135);
define("CURLOPT_IGNORE_CONTENT_LENGTH", 136);
define("CURLOPT_FTP_SKIP_PASV_IP", 137);
define("CURLOPT_FTP_FILEMETHOD", 138);
define("CURLOPT_CONNECT_ONLY", 141);
define("CURLOPT_LOCALPORT", 139);
define("CURLOPT_LOCALPORTRANGE", 140);
define("CURLFTPMETHOD_MULTICWD", 1);
define("CURLFTPMETHOD_NOCWD", 2);
define("CURLFTPMETHOD_SINGLECWD", 3);
define("CURLINFO_FTP_ENTRY_PATH", 1048606);
define("CURLOPT_FTP_ALTERNATIVE_TO_USER", 10147);
define("CURLOPT_MAX_RECV_SPEED_LARGE", 30146);
define("CURLOPT_MAX_SEND_SPEED_LARGE", 30145);
define("CURLE_SSL_CACERT_BADFILE", 77);
define("CURLOPT_SSL_SESSIONID_CACHE", 150);
define("CURLMOPT_PIPELINING", 3);
define("CURLE_SSH", 79);
define("CURLOPT_FTP_SSL_CCC", 154);
define("CURLOPT_SSH_AUTH_TYPES", 151);
define("CURLOPT_SSH_PRIVATE_KEYFILE", 10153);
define("CURLOPT_SSH_PUBLIC_KEYFILE", 10152);
define("CURLFTPSSL_CCC_ACTIVE", 2);
define("CURLFTPSSL_CCC_NONE", 0);
define("CURLFTPSSL_CCC_PASSIVE", 1);
define("CURLOPT_CONNECTTIMEOUT_MS", 156);
define("CURLOPT_HTTP_CONTENT_DECODING", 158);
define("CURLOPT_HTTP_TRANSFER_DECODING", 157);
define("CURLOPT_TIMEOUT_MS", 155);
define("CURLMOPT_MAXCONNECTS", 6);
define("CURLOPT_KRBLEVEL", 10063);
define("CURLOPT_NEW_DIRECTORY_PERMS", 160);
define("CURLOPT_NEW_FILE_PERMS", 159);
define("CURLOPT_APPEND", 50);
define("CURLOPT_DIRLISTONLY", 48);
define("CURLOPT_USE_SSL", 119);
define("CURLUSESSL_ALL", 3);
define("CURLUSESSL_CONTROL", 2);
define("CURLUSESSL_NONE", 0);
define("CURLUSESSL_TRY", 1);
define("CURLOPT_SSH_HOST_PUBLIC_KEY_MD5", 10162);
define("CURLOPT_PROXY_TRANSFER_MODE", 166);
define("CURLPAUSE_ALL", 5);
define("CURLPAUSE_CONT", 0);
define("CURLPAUSE_RECV", 1);
define("CURLPAUSE_RECV_CONT", 0);
define("CURLPAUSE_SEND", 4);
define("CURLPAUSE_SEND_CONT", 0);
define("CURL_READFUNC_PAUSE", 268435457);
define("CURL_WRITEFUNC_PAUSE", 268435457);
define("CURLPROXY_SOCKS4A", 6);
define("CURLPROXY_SOCKS5_HOSTNAME", 7);
define("CURLINFO_REDIRECT_URL", 1048607);
define("CURLINFO_APPCONNECT_TIME", 3145761);
define("CURLINFO_PRIMARY_IP", 1048608);
define("CURLOPT_ADDRESS_SCOPE", 171);
define("CURLOPT_CRLFILE", 10169);
define("CURLOPT_ISSUERCERT", 10170);
define("CURLOPT_KEYPASSWD", 10026);
define("CURLSSH_AUTH_ANY", -1);
define("CURLSSH_AUTH_DEFAULT", -1);
define("CURLSSH_AUTH_HOST", 4);
define("CURLSSH_AUTH_KEYBOARD", 8);
define("CURLSSH_AUTH_NONE", 0);
define("CURLSSH_AUTH_PASSWORD", 2);
define("CURLSSH_AUTH_PUBLICKEY", 1);
define("CURLINFO_CERTINFO", 4194338);
define("CURLOPT_CERTINFO", 172);
define("CURLOPT_PASSWORD", 10174);
define("CURLOPT_POSTREDIR", 161);
define("CURLOPT_PROXYPASSWORD", 10176);
define("CURLOPT_PROXYUSERNAME", 10175);
define("CURLOPT_USERNAME", 10173);
define("CURL_REDIR_POST_301", 1);
define("CURL_REDIR_POST_302", 2);
define("CURL_REDIR_POST_ALL", 7);
define("CURLAUTH_DIGEST_IE", 16);
define("CURLINFO_CONDITION_UNMET", 2097187);
define("CURLOPT_NOPROXY", 10177);
define("CURLOPT_PROTOCOLS", 181);
define("CURLOPT_REDIR_PROTOCOLS", 182);
define("CURLOPT_SOCKS5_GSSAPI_NEC", 180);
define("CURLOPT_SOCKS5_GSSAPI_SERVICE", 10179);
define("CURLOPT_TFTP_BLKSIZE", 178);
define("CURLPROTO_ALL", -1);
define("CURLPROTO_DICT", 512);
define("CURLPROTO_FILE", 1024);
define("CURLPROTO_FTP", 4);
define("CURLPROTO_FTPS", 8);
define("CURLPROTO_HTTP", 1);
define("CURLPROTO_HTTPS", 2);
define("CURLPROTO_LDAP", 128);
define("CURLPROTO_LDAPS", 256);
define("CURLPROTO_SCP", 16);
define("CURLPROTO_SFTP", 32);
define("CURLPROTO_TELNET", 64);
define("CURLPROTO_TFTP", 2048);
define("CURLPROXY_HTTP_1_0", 1);
define("CURLFTP_CREATE_DIR", 1);
define("CURLFTP_CREATE_DIR_NONE", 0);
define("CURLFTP_CREATE_DIR_RETRY", 2);
define("CURLOPT_SSH_KNOWNHOSTS", 10183);
define("CURLINFO_RTSP_CLIENT_CSEQ", 2097189);
define("CURLINFO_RTSP_CSEQ_RECV", 2097191);
define("CURLINFO_RTSP_SERVER_CSEQ", 2097190);
define("CURLINFO_RTSP_SESSION_ID", 1048612);
define("CURLOPT_FTP_USE_PRET", 188);
define("CURLOPT_MAIL_FROM", 10186);
define("CURLOPT_MAIL_RCPT", 10187);
define("CURLOPT_RTSP_CLIENT_CSEQ", 193);
define("CURLOPT_RTSP_REQUEST", 189);
define("CURLOPT_RTSP_SERVER_CSEQ", 194);
define("CURLOPT_RTSP_SESSION_ID", 10190);
define("CURLOPT_RTSP_STREAM_URI", 10191);
define("CURLOPT_RTSP_TRANSPORT", 10192);
define("CURLPROTO_IMAP", 4096);
define("CURLPROTO_IMAPS", 8192);
define("CURLPROTO_POP3", 16384);
define("CURLPROTO_POP3S", 32768);
define("CURLPROTO_RTSP", 262144);
define("CURLPROTO_SMTP", 65536);
define("CURLPROTO_SMTPS", 131072);
define("CURL_RTSPREQ_ANNOUNCE", 3);
define("CURL_RTSPREQ_DESCRIBE", 2);
define("CURL_RTSPREQ_GET_PARAMETER", 8);
define("CURL_RTSPREQ_OPTIONS", 1);
define("CURL_RTSPREQ_PAUSE", 6);
define("CURL_RTSPREQ_PLAY", 5);
define("CURL_RTSPREQ_RECEIVE", 11);
define("CURL_RTSPREQ_RECORD", 10);
define("CURL_RTSPREQ_SET_PARAMETER", 9);
define("CURL_RTSPREQ_SETUP", 4);
define("CURL_RTSPREQ_TEARDOWN", 7);
define("CURLINFO_LOCAL_IP", 1048617);
define("CURLINFO_LOCAL_PORT", 2097194);
define("CURLINFO_PRIMARY_PORT", 2097192);
define("CURLOPT_FNMATCH_FUNCTION", 20200);
define("CURLOPT_WILDCARDMATCH", 197);
define("CURLPROTO_RTMP", 524288);
define("CURLPROTO_RTMPE", 2097152);
define("CURLPROTO_RTMPS", 8388608);
define("CURLPROTO_RTMPT", 1048576);
define("CURLPROTO_RTMPTE", 4194304);
define("CURLPROTO_RTMPTS", 16777216);
define("CURL_FNMATCHFUNC_FAIL", 2);
define("CURL_FNMATCHFUNC_MATCH", 0);
define("CURL_FNMATCHFUNC_NOMATCH", 1);
define("CURLPROTO_GOPHER", 33554432);
define("CURLAUTH_ONLY", 2147483648);
define("CURLOPT_RESOLVE", 10203);
define("CURLOPT_TLSAUTH_PASSWORD", 10205);
define("CURLOPT_TLSAUTH_TYPE", 10206);
define("CURLOPT_TLSAUTH_USERNAME", 10204);
define("CURL_TLSAUTH_SRP", 1);
define("CURLOPT_ACCEPT_ENCODING", 10102);
define("CURLOPT_TRANSFER_ENCODING", 207);
define("CURLAUTH_NTLM_WB", 32);
define("CURLGSSAPI_DELEGATION_FLAG", 2);
define("CURLGSSAPI_DELEGATION_POLICY_FLAG", 1);
define("CURLOPT_GSSAPI_DELEGATION", 210);
define("CURLOPT_ACCEPTTIMEOUT_MS", 212);
define("CURLOPT_DNS_SERVERS", 10211);
define("CURLOPT_MAIL_AUTH", 10217);
define("CURLOPT_SSL_OPTIONS", 216);
define("CURLOPT_TCP_KEEPALIVE", 213);
define("CURLOPT_TCP_KEEPIDLE", 214);
define("CURLOPT_TCP_KEEPINTVL", 215);
define("CURLSSLOPT_ALLOW_BEAST", 1);
define("CURL_REDIR_POST_303", 4);
define("CURLSSH_AUTH_AGENT", 16);
define("CURLMOPT_CHUNK_LENGTH_PENALTY_SIZE", 30010);
define("CURLMOPT_CONTENT_LENGTH_PENALTY_SIZE", 30009);
define("CURLMOPT_MAX_HOST_CONNECTIONS", 7);
define("CURLMOPT_MAX_PIPELINE_LENGTH", 8);
define("CURLMOPT_MAX_TOTAL_CONNECTIONS", 13);
define("CURLOPT_SASL_IR", 218);
define("CURLOPT_DNS_INTERFACE", 10221);
define("CURLOPT_DNS_LOCAL_IP4", 10222);
define("CURLOPT_DNS_LOCAL_IP6", 10223);
define("CURLOPT_XOAUTH2_BEARER", 10220);
define("CURL_HTTP_VERSION_2_0", 3);
define("CURL_VERSION_HTTP2", 65536);
define("CURLOPT_LOGIN_OPTIONS", 10224);
define("CURL_SSLVERSION_TLSv1_0", 4);
define("CURL_SSLVERSION_TLSv1_1", 5);
define("CURL_SSLVERSION_TLSv1_2", 6);
define("CURLOPT_EXPECT_100_TIMEOUT_MS", 227);
define("CURLOPT_SSL_ENABLE_ALPN", 226);
define("CURLOPT_SSL_ENABLE_NPN", 225);
define("CURLHEADER_SEPARATE", 1);
define("CURLHEADER_UNIFIED", 0);
define("CURLOPT_HEADEROPT", 229);
define("CURLOPT_PROXYHEADER", 10228);
define("CURLAUTH_NEGOTIATE", 4);
define("CURLOPT_PINNEDPUBLICKEY", 10230);
define("CURLOPT_UNIX_SOCKET_PATH", 10231);
define("CURLPROTO_SMB", 67108864);
define("CURLPROTO_SMBS", 134217728);
define("CURLOPT_SSL_VERIFYSTATUS", 232);
define("CURLOPT_PATH_AS_IS", 234);
define("CURLOPT_SSL_FALSESTART", 233);
define("CURL_HTTP_VERSION_2", 3);
define("CURLOPT_PIPEWAIT", 237);
define("CURLOPT_PROXY_SERVICE_NAME", 10235);
define("CURLOPT_SERVICE_NAME", 10236);
define("CURLPIPE_NOTHING", 0);
define("CURLPIPE_HTTP1", 1);
define("CURLPIPE_MULTIPLEX", 2);
define("CURLSSLOPT_NO_REVOKE", 2);
define("CURLOPT_DEFAULT_PROTOCOL", 10238);
define("CURLOPT_STREAM_WEIGHT", 239);
define("CURLMOPT_PUSHFUNCTION", 20014);
define("CURL_PUSH_OK", 0);
define("CURL_PUSH_DENY", 1);
define("CURL_HTTP_VERSION_2TLS", 4);
define("CURLOPT_TFTP_NO_OPTIONS", 242);
define("CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE", 5);
define("CURLOPT_CONNECT_TO", 10243);
define("CURLOPT_TCP_FASTOPEN", 244);
define("CURLOPT_SAFE_UPLOAD", -1);


if(!function_exists('curl_init')){

   /**
    * --RU--
    * Инициализирует сеанс cURL
    * @param string $url (optional)
    */
    function curl_init($url = NULL){
        return new jURL($url);
    }

   /**
    * --RU--
    * Устанавливает параметр для сеанса CURL
    * @param jURL $ch - Дескриптор cURL, полученный из curl_init
    * @param string $key - Устанавливаемый параметр CURLOPT_*
    * @param string $value - Значение параметра key
    */
    function curl_setopt(jURL $ch, $key, $value){
        // Теперь константы имеют исключительно int значение, как в оригинальном php
        $key = is_string($key) ? (defined($key) ? constant($key) : $key) : $key;
        
        $reKeys = [
             CURLOPT_URL => 'url',
             CURLOPT_CONNECTTIMEOUT => 'connectTimeout',
             CURLOPT_CONNECTTIMEOUT_MS => 'connectTimeout',
             CURLOPT_TIMEOUT => 'readTimeout',
             CURLOPT_TIMEOUT_MS => 'readTimeout',
             CURLOPT_CUSTOMREQUEST => 'requestMethod',
             CURLOPT_POSTFIELDS => 'postData', // postFiles //
             CURLOPT_POST => 'requestMethod',
             CURLOPT_PUT => 'requestMethod',
             CURLOPT_REFERER => 'httpReferer',
             CURLOPT_AUTOREFERER => 'autoReferer',
             CURLOPT_COOKIEFILE => 'cookieFile',
             CURLOPT_COOKIEJAR => 'cookieFile',
             CURLOPT_USERAGENT => 'userAgent',
             CURLOPT_HEADER => 'returnHeaders',
             CURLOPT_FOLLOWLOCATION => 'followRedirects',
             CURLOPT_HTTPHEADER => 'httpHeader',
             CURLOPT_USERPWD => 'basicAuth',
             CURLOPT_PROXY => 'proxy',
             CURLOPT_PROXYUSERPWD => 'proxyAuth',
             CURLOPT_PROXYTYPE => 'proxyType',
             CURLOPT_PROGRESSFUNCTION => 'progressFunction',
             CURLOPT_FILE => 'outputFile',
             CURLOPT_BUFFERSIZE => 'bufferLength',
             CURLOPT_INFILE => 'inputFile',
             CURLOPT_NOBODY => 'returnBody',
       ];
       
       // Переводим curl ключи в jurl ключи
       $jKey = isset($reKeys[$key]) ? $reKeys[$key] : NULL;
       
        switch($key){
            case CURLOPT_POST:
                if(boolval($value)) $value = 'POST';
                break;             

            case CURLOPT_PUT:
                if(boolval($value)) $value = 'PUT';
                break;           

            case CURLOPT_NOBODY:
                $value = !$value;
                break;

            case CURLOPT_CONNECTTIMEOUT:
            case CURLOPT_TIMEOUT:
                $value *= 1000;
                break;

            case CURLOPT_HTTPHEADER:
                foreach ($value as $k => $h) {
                    $t = Str::split($h, ':', 2);
                    $value[$k] = [ Str::trim($t[0]), Str::trim($t[1]) ];
                }
                break;

            case CURLOPT_POSTFIELDS:
                if(!is_array($value)) break;

                // Разбиваем элементы на строковые значения и файлы
                $str = [];
                $files = [];

                foreach($value as $k=>$v){
                    if($v instanceof jURLFile || Str::Sub($v, 0, 1) == '@'){
                        $files[$k] = $v; 
                    }
                    else $str[$k] = $v;
                }
                if(sizeof($files) > 0) $ch->setOpt('postFiles', $files);
                $value = $str;
                break;

            case CURLOPT_PROXYTYPE:
                $value = is_string($value) ? (defined($value) ? constant($value) : $value) : $value;  

                switch ($value) {
                    case CURLPROXY_SOCKS4:
                    case CURLPROXY_SOCKS4A:
                    case CURLPROXY_SOCKS5:
                    case CURLPROXY_SOCKS5_HOSTNAME:
                        $value = 'SOCKS';
                    break;
                    case CURLPROXY_HTTP:
                    case CURLPROXY_HTTP_1_0:
                    default:
                        $value = 'HTTP';
                }

                break;
        }      
        $ch->setOpt($jKey, $value);
    }


    /**
     * --RU--
     * Устанавливает несколько параметров для сеанса cURL
     * @param jURL $ch - Дескриптор cURL, полученный из curl_init
     * @param array $options - Массив c параметрами вида [CURLOPT_* => 'value']
     */
    function curl_setopt_array(jURL $ch, $options){
        foreach($options as $k=>$v){
            curl_setopt($ch, $k, $v);
        }
    } 

    /**
     * --RU--
     * Выполняет запрос cURL
     * @param jURL $ch - Дескриптор cURL, полученный из curl_init
     * @return mixed
     */
    function curl_exec(jURL $ch){
       try{
          return $ch->exec();
       } catch(jURLException $e){
          return false;
       }
    }

    /**
     * --RU--
     * Выполняет запрос cURL асинхронно
     * @param jURL $ch - Дескриптор cURL, полученный из curl_init
     * @param callable $callback - Функция, куда бкдет передан результат запроса
     */
    function curl_exec_async(jURL $ch, $callback = null){
       try{
          $ch->aSyncExec($callback);
       } catch(jURLException $e){
          if(is_callable($callback)){
             $callback(false);
          }
       }
        
    }

    /**
     * --RU--
     * Возвращает строку с описанием последней ошибки текущего сеанса
     * @param jURL $ch - Дескриптор cURL, полученный из curl_init
     * @return string|null
     */
    function curl_error(jURL $ch){
        return ($ch->getError() === false) ? null : $ch->getError()['error'];
    }

    /**
     * --RU--
     * Возвращает код последней ошибки
     * @param jURL $ch - Дескриптор cURL, полученный из curl_init
     * @return int - Код ошибки или 0, если запрос выполнен без ошибок
     */
    function curl_errno(jURL $ch){
        return ($ch->getError() === false) ? 0 : $ch->getError()['code'];
    }

    /**
     * --RU--
     * Возвращает информацию об определенной операции
     * @param jURL $ch - Дескриптор cURL, полученный из curl_init
     * @return array
     * @todo Сделать поддержку оригинальных CURLINFO_ ключей
     */
    function curl_getinfo(jURL $ch, $opt = null){
    	/**
    	 * Ключи CURLINFO соответствующие ключам jURL
    	 * @todo
    	 */
        $cjKeys = [
        	CURLINFO_EFFECTIVE_URL => 'url',
        	CURLINFO_REDIRECT_URL => 'redirectUrls',
        	CURLINFO_HTTP_CODE => 'responseCode',
        	CURLINFO_TOTAL_TIME => 'executeTime', // sec => msec
        	CURLINFO_CONNECT_TIME => 'connectTime', // sec => msec
        	CURLINFO_REDIRECT_COUNT => 'redirectNum',
        	// CURLINFO_PRIMARY_IP => 'host',
        	// CURLINFO_PRIMARY_PORT => 'port',
        	CURLINFO_CONTENT_LENGTH_DOWNLOAD => 'contentLength',
        	CURLINFO_SIZE_DOWNLOAD => 'responseLength',
        	CURLINFO_SIZE_UPLOAD => 'requestLength',
        	CURLINFO_SPEED_DOWNLOAD => 'responseLength',
        	CURLINFO_SPEED_UPLOAD => 'requestLength',
        	CURLINFO_CONTENT_TYPE => 'contentType',
        	CURLINFO_FILETIME => 'lastModified',

        	CURLINFO_HEADER_OUT => 'requestHeaders',	// отправленные зеголовки
        	CURLINFO_HEADER_SIZE  => 'requestHeaderLength',	// размер полученных заголовков
        	// CURLINFO_REQUEST_SIZE  => 'requestHeaders', // размер отправленных заголовков
        ];

        /**
         * Ключи, которые будут возвращены при не указанном параметре $opt == null
         * @todo Добавить ключи
         * ""
		 * ""
		 * "ssl_verify_result"
		 * "namelookup_time"
		 * "pretransfer_time"
		 * "upload_content_length"
		 * "starttransfer_time"
		 * "redirect_time"
		 * "certinfo"
		 * "primary_ip"
		 * "primary_port"
		 * "local_ip"
		 * "local_port"
		 * ""
         */
        $textKeys = [
        	CURLINFO_EFFECTIVE_URL => 'url',
        	CURLINFO_CONTENT_TYPE => 'content_type',
        	CURLINFO_HTTP_CODE => 'http_code',
        	// CURLINFO_SIZE_DOWNLOAD => '',
        	CURLINFO_SIZE_DOWNLOAD => 'size_download',
        	CURLINFO_FILETIME => 'filetime',
        	CURLINFO_REDIRECT_COUNT => 'redirect_count',
        	CURLINFO_TOTAL_TIME => 'total_time', // sec => msec
        	CURLINFO_CONNECT_TIME => 'connect_time', // sec => msec
        	CURLINFO_SIZE_UPLOAD => 'size_upload',
        	CURLINFO_SPEED_UPLOAD => 'speed_upload',
        	CURLINFO_SPEED_DOWNLOAD => 'speed_download',
        	CURLINFO_CONTENT_LENGTH_DOWNLOAD => 'download_content_length',
        	CURLINFO_REDIRECT_URL => 'redirect_url',

        	CURLINFO_HEADER_OUT => 'request_header',
        	 CURLINFO_HEADER_SIZE => 'header_size',
        	// CURLINFO_REQUEST_SIZE => 'request_size',
        ];

        $jinfo = $ch->getConnectionInfo();
        $info = [];

        foreach ($cjKeys as $key => $value){
        	if(!is_null($opt) and $opt != $key) continue;
        	$item = null;
        	switch ($key){
        		case CURLINFO_REDIRECT_URL:
        			$arr = $jinfo[$value];
        			$item = (sizeof($arr) > 0) ? end($arr) : null;
        			break;        		

        		case CURLINFO_HEADER_OUT:
        			if(!is_array($jinfo[$value])) break;
        			foreach($jinfo[$value] as $k => $v){
        				$pre = (strlen($k) > 0) ? $k . ': ' : $k ;
        				foreach ($v as $vv) {
        					$item .= $pre . $vv . "\r\n";
        				}
        			}
        			$item .= "\r\n";
        			break;

           		case CURLINFO_TOTAL_TIME:
        		case CURLINFO_CONNECT_TIME:
        			$item = $jinfo[$value] / 1000;
        			break;

        		case CURLINFO_REDIRECT_COUNT:
        			$item = $jinfo[$value] ?? 0;
        			break;

        		case CURLINFO_SPEED_UPLOAD:
        		case CURLINFO_SPEED_DOWNLOAD:
        			$item = ($jinfo['executeTime'] == 0) ? $jinfo['executeTime'] : ($jinfo[$value] / $jinfo['executeTime']);
        			break;
        		
        		default:
        			$item = $jinfo[$value] ?? null;
        	}

        	if(is_null($opt)) $info[$textKeys[$key]] = $item;
        	else return $item;
        }

	    return $info;
    }

    
    /**
     * --RU--
     * Завершает сеанс cURL
     * @param jURL $ch - Дескриптор cURL, полученный из curl_init
     */
    function curl_close(jURL $ch){
        return $ch->close();
    }

    /**
     * --RU--
     * Сбросить параметры текущего сеанса
     */
    function curl_reset(jURL $ch){
        return $ch->reset();
    }


    /**
     * --RU--
     * Создает объект cURLFile
     * @param string $filename Path to the file which will be uploaded.
     * @param string $mimetype = NULL
     * @param string $postname = NULL
     * @return cURLFile
     */
    function curl_file_create($filename, $mimetype = NULL, $postname = NULL){
        return new cURLFile($filename, $mimetype, $postname);
    }
}

if(!function_exists('http_build_query')){
    function http_build_query($a,$b='',$c=0){
        if (!is_array($a)) return $a;

        foreach ($a as $k=>$v){
            if($c){
                if( is_numeric($k) ){
                    $k=$b."[]";
                } else {
                    $k=$b."[$k]";
                }
            } else {   
                if (is_int($k)){
                    $k=$b.$k;
                }
            }
            if (is_array($v)||is_object($v)){
                $r[] = http_build_query($v,$k,1);
                continue;
            }
            $r[] = urlencode($k) . "=" . urlencode($v);
        }
        return implode("&",$r);
    }
}

if(!function_exists('parse_str')){
    function parse_str($str) {
        $arr = array();
        $pairs = explode('&', $str);

        foreach ($pairs as $i) {
            list($name,$value) = explode('=', $i, 2);

                if( isset($arr[$name]) ) {
                    if( is_array($arr[$name]) ) {
                        $arr[$name][] = $value;
                    } else {
                        $arr[$name] = array($arr[$name], $value);
                    }
                } else {
                    $arr[$name] = $value;
                }
        }
        return $arr;
    }
}