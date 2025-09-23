<?php
class ConstUtils
{
    // Constants
    public const FIELD_LABEL_ID = 'id';
    public const FIELD_LABEL_FIRSTNAME = 'firstname';
    public const FIELD_LABEL_LASTNAME = 'lastname';
    public const FIELD_LABEL_EMAIL = 'email';
    public const FIELD_LABEL_PASSWORD = 'password';
    public const FIELD_LABEL_NAME = 'name';
    public const FIELD_LABEL_SECTION_ID = 'section_id';
    public const FIELD_LABEL_RECOVERY_TOKEN = 'token';
    public const FIELD_LABEL_EXPIRES_AT = 'expires_at';
    public const FIELD_LABEL_CREATED_AT = 'created_at';
    public const FIELD_LABEL_USER_ID = 'user_id';
    public const BLANK_STRING = '';
    public const POST_METHOD = 'POST';
    public const REQUEST_METHOD = 'REQUEST_METHOD';
    public const SESSION_USER = '__session_user';
    public const STATUS = 'status';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR_NO_USER = 'error_no_user';
    public const STATUS_ERROR = 'error';
    public const STATUS_ERROR_EMAIL_IN_USE = 'error_email_in_use';
    public const STATUS_ERROR_TOKEN_EXPIRED = 'error_token_expired';
    public const GET_PARAMETER_TOKEN = 'token';
    public const FIELD_LABEL_PRODUCENT = 'producent';
    public const FIELD_LABEL_PRICE = 'price';
    public const FIELD_LABEL_DISCOUNT = 'discount';
    public const FIELD_LABEL_IMAGE_NAME = 'image_name';
    public const FIELD_LABEL_COMMENT = 'comment';
    public const FIELD_LABEL_STARS = 'stars';
    public const FIELD_LABEL_PRODUCT_ID = 'product_id';
    public const DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const FIELD_LABEL_RATE_RATE = 'rare_rate';
    public const FIELD_LABEL_PERCENT = 'percent';
    public const REWARD_PRODUCT_ID = 'reward_product_id';
    public const NEXT_SPIN_DATE_FORMAT = 'Y-m-d H:i';
    public const FIELD_LABEL_SECTION_NAME = 'section_name';
    public const FIELD_LABEL_DESCRIPTION = 'description';
    public const FIELD_LABEL_ARCHIVED = 'archived';
    public const FIELD_LABEL_CATEGORY_ID = 'category_id';
    public const FIELD_LABEL_CATEGORY_NAME = 'category_name';
    public const RECORD_PER_PAGE = 12;
    public const FIELD_LABEL_AVG_OPINION = 'average_rating';
    public const FIELD_LABEL_OPINION_COUNT = 'opinion_count';
    public const FIELD_LABEL_QUANTITY = 'quantity';
    public const SESSION_USER_CART = '__session_user_cart';
    public const PREV_PAGE = '__prev_page';
    public static function getCurrentUrl()
    {
        $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $currentUrl .= "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return $currentUrl;
    }
}
