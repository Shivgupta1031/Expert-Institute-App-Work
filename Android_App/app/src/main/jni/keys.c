#include <jni.h>

JNIEXPORT jstring JNICALL
Java_com_devshiv_expertinstitute_utils_ApiConstants_getAuthToken(JNIEnv *authToken, jobject instance) {
    return (*authToken)-> NewStringUTF(authToken, "DkRObU5qUXlNjgdfTkNZcUpqY3hOalF0TkfdRCelpGNG1PVFpoWlMweE56a3fdfddfRFeFlUWT0=");
}

JNIEXPORT jstring JNICALL
Java_com_devshiv_expertinstitute_utils_ApiConstants_getApiUrl(JNIEnv *apiUrl, jobject instance) {
    return (*apiUrl)->NewStringUTF(apiUrl, "https://app.expertinstitute.info/api/");
}

JNIEXPORT jstring JNICALL
Java_com_devshiv_expertinstitute_utils_ApiConstants_getApiKey(JNIEnv *apiKey, jobject instance) {
    return (*apiKey)-> NewStringUTF(apiKey, "AAAAAAAAdfhcgwcEQkB");
}