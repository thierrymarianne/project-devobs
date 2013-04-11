<?php

if ( ! function_exists( 'assignConstant' ) )
{
    $exception = '';

    if ( defined( 'ENTITY_FUNCTION' ) )
        $exception = sprintf( EXCEPTION_MISSING_ENTITY, ENTITY_FUNCTION );

    throw new Exception( $exception );
}

$properties = array(
    'PROPERTY_ACCESS_KEY' => 'accesskey',
    'PROPERTY_ACCESS_TYPE' => 'access_type',
    'PROPERTY_ACTION' => 'action',
    'PROPERTY_AFFORDANCE' => 'affordance',
    'PROPERTY_ALIAS' => 'alias',
    'PROPERTY_ANONYMOUS' => 'anonymous',
    'PROPERTY_ANY' => 'any',
    'PROPERTY_API_CONSUMER_CALLBACK' => 'api_consumer_callback',
    'PROPERTY_API_CONSUMER_KEY' => 'api_consumer_key',
    'PROPERTY_API_CONSUMER_SECRET' => 'api_consumer_secret',
    'PROPERTY_ARGUMENT' => 'argument',
    'PROPERTY_ARGUMENTS' => 'arguments',
    'PROPERTY_ATTRIBUTES' => 'attributes',
    'PROPERTY_AUTHOR' => 'author',
    'PROPERTY_AVATAR' => 'avatar',
    'PROPERTY_BACKUP' => 'backup',
    'PROPERTY_BLANKS' => 'blanks',
    'PROPERTY_BODY' => 'body',
    'PROPERTY_BODY_HTML' => 'body_html',
    'PROPERTY_BODY_TEXT' => 'body_text',
    'PROPERTY_BRACKET_CLOSING' => 'closing_bracket',
    'PROPERTY_BRACKET_OPENING' => 'opening_bracket',
    'PROPERTY_SIZE_CHUNK_MAX' => 'max_chunk_size',
    'PROPERTY_CACHE_ID' => 'cache_id',
    'PROPERTY_CALLBACK' => 'callback',
    'PROPERTY_CALLBACK_METHOD' => 'callback.method',
    'PROPERTY_CALLEE' => 'callee',
    'PROPERTY_CELL' => 'cell',
    'PROPERTY_CHECK' => 'check',
    'PROPERTY_CHILDREN' => 'children',
    'PROPERTY_CLASS' => 'class',
    'PROPERTY_CLEAN_UP' => 'clean_up',
    'PROPERTY_COLUMN' => 'column',
    'PROPERTY_COLUMN_PREFIX' => 'column_prefix',
    'PROPERTY_COMPASS' => 'compass',
    'PROPERTY_COMPONENT' => 'component',
    'PROPERTY_COMPUTATION' => 'computation',
    'PROPERTY_CONFIGURATION_FILE' => 'configuration_file',
    'PROPERTY_CONFIGURATION' => 'configuration',
    'PROPERTY_CONNECTOR' => 'connector',
    'PROPERTY_CONDITION_FIELD_VALUE_CONFIRMED' => 'condition_field_value_confirmed',
    'PROPERTY_CONDITION_FIELD_VALUE_ERROR' => 'condition_field_value_error',
    'PROPERTY_CONDITION_FIELD_VALUE_MISSING' => 'condition_field_value_missing',
    'PROPERTY_CONDITION_FIELD_VALUE_STORED' => 'condition_field_value_stored',
    'PROPERTY_CONDITION_FIELD_VALUE_UNCONFIRMED' => 'condition_field_value_unconfirmed',
    'PROPERTY_CONDITION_VALIDATION_FAILURE' => 'condition_validation_failure',
    'PROPERTY_CONDITIONS' => 'conditions',
    'PROPERTY_CONSISTENT' => 'consistent',
    'PROPERTY_CONSTANT' => 'constant',
    'PROPERTY_CONTAINER_REFERENCES' => 'references_container',
    'PROPERTY_CONTAINER' => 'container',
    'PROPERTY_CONTENT' => 'content',
    'PROPERTY_CONTENT_TYPE' => 'content_type',
    'PROPERTY_CONTEXT' => 'context',
    'PROPERTY_COORDINATES' => 'coordinates',
    'PROPERTY_COUNT_BYTES' => 'bytes_count',
    'PROPERTY_COUNT_OPTIONS' => 'options_count',
    'PROPERTY_COUNT_TOKENS' => 'tokens_count',
    'PROPERTY_COUNT' => 'count',
    'PROPERTY_COVERAGE_FULL' => 'full_coverage',
    'PROPERTY_DASHBOARD' => 'dashboard',
    'PROPERTY_DATA' => 'data',
    'PROPERTY_DATA_CONFIRMED' => 'confirmed_data',
    'PROPERTY_DATA_POSTED' => 'posted_data',
    'PROPERTY_DATA_SUBMISSION' => 'data_submission',
    'PROPERTY_DATA_SUBMITTED' => 'submitted_data',
    'PROPERTY_DATA_VALIDATED' => 'validated_data',
    'PROPERTY_DATA_VALIDATION' => 'data_validation',
    'PROPERTY_DATA_VALIDATION_FAILURE' => 'data_validation_failure',
    'PROPERTY_DATABASE' => 'database',
    'PROPERTY_DATE_CREATION' => 'date_creation',
    'PROPERTY_DATE_LAST_OCCURRENCE' => 'date_last_occurrence',
    'PROPERTY_DATE_MODIFICATION' => 'date_modification',
    'PROPERTY_DESTINATION' => 'destination',
    'PROPERTY_DISCLAIMERS' => 'disclaimers',
    'PROPERTY_DEFAULT' => 'default',
    'PROPERTY_DEFINITION_LARGE' => 'large_definition',
    'PROPERTY_DEFINITION' => 'definition',
    'PROPERTY_DELIMITERS_CLOSING' => 'closing_delimiters',
    'PROPERTY_DELIMITERS_MAPPING' => 'mapping_delimiters',
    'PROPERTY_DELIMITERS_OPENING' => 'opening_delimiters',
    'PROPERTY_DESCRIPTION' => 'description',
    'PROPERTY_DIV' => 'div',
    'PROPERTY_DOM_ATTRIBUTES' => 'attributes',
    'PROPERTY_DOM_CHILD_NODES' => 'childNodes',
    'PROPERTY_DOM_DOCUMENT' => 'dom_document',
    'PROPERTY_DOM_ELEMENT' => 'dom_element',
    'PROPERTY_DOM_ELEMENT_TAG_NAME' => 'tagName',
    'PROPERTY_DOM_NODE_NAME' => 'nodeName',
    'PROPERTY_DOM_NODE_VALUE' => 'nodeValue',
    'PROPERTY_DOMAIN' => 'domain',
    'PROPERTY_EDITION' => 'edition',
    'PROPERTY_EDITION_MODE' => 'edition_mode',
    'PROPERTY_ELEMENTS_PROPERTIES' => 'elements_properties',
    'PROPERTY_ENCAPSULATION' => 'encapsulation',
    'PROPERTY_ENDPOINT' => 'endpoint',
    'PROPERTY_END' => 'end',
    'PROPERTY_ENTITY' => 'entity',
    'PROPERTY_ENTITY_NAME' => 'entity_name',
    'PROPERTY_ENTITY_TYPE' => 'entity_type',
    'PROPERTY_ERROR_SYNTAX' => 'syntax_error',
    'PROPERTY_ERROR' => 'error',
    'PROPERTY_ERRORS' => 'errors',
    'PROPERTY_EXCEPTION' => 'exception',
    'PROPERTY_EXCERPT' => 'excerpt',
    'PROPERTY_EXPRESSION' => 'expression',
    'PROPERTY_EVALUATION' => 'evaluation',
    'PROPERTY_FAILURE' => 'failure',
    'PROPERTY_FEEDBACK' => 'feedback',
    'PROPERTY_FEED' => 'feed',
    'PROPERTY_FIELD' => 'field',
    'PROPERTY_FIELD_CONTEXT' => 'context_field',
    'PROPERTY_FIELD_FIRST_INDEX' => 'first_field_index',
    'PROPERTY_FIELD_HANDLER' => 'field_handler',
    'PROPERTY_FIELDS' => 'fields',
    'PROPERTY_FIELD_VALUES' => 'field_values',
    'PROPERTY_FILE' => 'file',
    'PROPERTY_FILES_NAMES' => 'files_names',
    'PROPERTY_FILTER' => 'filter',
    'PROPERTY_FOLDER' => 'folder',
    'PROPERTY_FORMAT' => 'format',
    'PROPERTY_FORM' => 'form',
    'PROPERTY_FORM_DESCRIPTION' => 'form_description',
    'PROPERTY_FORM_CONFIGURATION' => 'form_configuration',
    'PROPERTY_FORM_IDENTIFIER' => 'form_identifier',
    'PROPERTY_FOREIGN_KEY' => 'foreign_key',
    'PROPERTY_FUNCTION' => 'function',
    'PROPERTY_HANDLE' => 'handle',
    'PROPERTY_HANDLER' => 'handler',
    'PROPERTY_HANDLER_STATUS' => 'handler_status',
    'PROPERTY_HANDLERS' => 'handlers',
    'PROPERTY_HASH_MAP' => 'hash_map',
    'PROPERTY_HASH' => 'hash',
    'PROPERTY_HASHES' => 'hashes',
    'PROPERTY_HEIGHT' => 'height',
    'PROPERTY_HOST' => 'host',
    'PROPERTY_HTML_ELEMENTS' => 'html_elements',
    'PROPERTY_ID' => 'id',
    'PROPERTY_IDENTIFIER' => 'identifier',
    'PROPERTY_IDENTITY' => 'identity',
    'PROPERTY_IMAGE' => 'image',
    'PROPERTY_IMAP_MESSAGE_NUMBER' => 'imap_message_number',
    'PROPERTY_IMAP_UID' => 'imap_uid',
    'PROPERTY_INDEX_FIRST' => 'first_index',
    'PROPERTY_INDEX_LAST' => 'last_index',
    'PROPERTY_INDEX_SECTION' => 'section_index',
    'PROPERTY_INDEX' => 'index',
    'PROPERTY_INPUT' => 'input',
    'PROPERTY_INSTANCE' => 'instance',
    'PROPERTY_IS_NULL' => 'is_null',
    'PROPERTY_ISA' => 'isa',
    'PROPERTY_ISBN' => 'isbn',
    'PROPERTY_KEY' => 'key',
    'PROPERTY_KEYS' => 'keys',
    'PROPERTY_KEYWORDS' => 'keywords',
    'PROPERTY_KIND' => 'kind',
    'PROPERTY_HEADER' => 'header',
    'PROPERTY_HISTORY' => 'history',
    'PROPERTY_HOST' => 'host',
    'PROPERTY_LABEL' => 'label',
    'PROPERTY_LANGUAGE' => 'language',
    'PROPERTY_LAST_INSERT_ID' => 'last_insert_id',
    'PROPERTY_LAST_UID_RECORDED' => 'last_recorded_uid',
    'PROPERTY_LAYOUT' => 'layout',
    'PROPERTY_LEFT_MEMBER' => 'left.member',
    'PROPERTY_LEFT_OPERAND' => 'left.operand',
    'PROPERTY_LENGTH_HASH' => 'hash_length',
    'PROPERTY_LENGTH_MAX' => 'max_length',
    'PROPERTY_LENGTH' => 'length',
    'PROPERTY_LEVEL' => 'level',
    'PROPERTY_LEVELS' => 'levels',
    'PROPERTY_LIMIT' => 'limit',
    'PROPERTY_LINE' => 'line',
    'PROPERTY_LINK' => 'link',
    'PROPERTY_LINK_MYSQLI' => 'link_mysqli',
    'PROPERTY_LINKS' => 'links',
    'PROPERTY_LOCATION' => 'location',
    'PROPERTY_LOCAL' => 'local',
    'PROPERTY_LOCKED' => 'locked',
    'PROPERTY_LOGIN' => 'login',
    'PROPERTY_MATCH' => 'matching_values',
    'PROPERTY_MAX_ID' => 'max_id',
    'PROPERTY_MANDATORY' => 'mandatory',
    'PROPERTY_METADATA' => 'metadata',
    'PROPERTY_MEMBER' => 'member',
    'PROPERTY_MEMBERSHIP' => 'membership',
    'PROPERTY_MESSAGE' => 'message',
    'PROPERTY_METHOD' => 'method',
    'PROPERTY_METHODS' => 'methods',
    'PROPERTY_MODE_ACCESS' => 'access_mode',
    'PROPERTY_NAME' => 'name',
    'PROPERTY_NAME_TRIMMED' => 'trimmed_name',
    'PROPERTY_NAMESPACE' => 'namespace',
    'PROPERTY_NECESSARY' => 'necessary',
    'PROPERTY_NODE' => 'node',
    'PROPERTY_NULL' => 'null',
    'PROPERTY_OBJECT' => 'object',
    'PROPERTY_OCCURRENCES' => 'occurrences',
    'PROPERTY_OCCURRENCE' => 'occurrence',
    'PROPERTY_OCCURRENCE_LAST' => 'last_occurrence',
    'PROPERTY_OFFSET' => 'offset',
    'PROPERTY_OPERAND' => 'operand',
    'PROPERTY_OPERANDS' => 'operands',
    'PROPERTY_OPTIONS' => 'options',
    'PROPERTY_OUTPUT' => 'output',
    'PROPERTY_OVERVIEW' => 'overview',
    'PROPERTY_OBJECT' => 'object',
    'PROPERTY_OWNER' => 'owner',
    'PROPERTY_OWNERSHIP' => 'ownership',
    'PROPERTY_PAGE' => 'page',
    'PROPERTY_PARAMETER' => 'parameter',
    'PROPERTY_PARENT' => 'parent',
    'PROPERTY_PARENT_HUB' => 'parent_hub',
    'PROPERTY_PARENT_ROOT' => 'parent_root',
    'PROPERTY_PASSWORD' => 'password',
    'PROPERTY_PATH_FILE' => 'file_path',
    'PROPERTY_PATH' => 'path',
    'PROPERTY_PATTERN' => 'pattern',
    'PROPERTY_PDO' => 'pdo',
    'PROPERTY_PARENTHESIS_CLOSING' => 'closing_parenthesis',
    'PROPERTY_PARENTHESIS_OPENING' => 'opening_parenthesis',
    'PROPERTY_PERSISTENCY' => 'persistency',
    'PROPERTY_PLACEHOLDERS' => 'placeholders',
    'PROPERTY_POSITION_NEW' => 'new_position',
    'PROPERTY_POSITION_OLD' => 'old_position',
    'PROPERTY_POSITION' => 'position',
    'PROPERTY_PREVIEW' => 'preview',
    'PROPERTY_PRIMARY_KEY' => 'primary_key',
    'PROPERTY_PRIVILEGE' => 'privilege',
    'PROPERTY_PROPERTIES' => 'properties',
    'PROPERTY_PROPERTY' => 'property',
    'PROPERTY_PROPERTY_NAME' => 'property_name',
    'PROPERTY_PROTOCOL' => 'protocol',
    'PROPERTY_PUBLIC' => 'public',
    'PROPERTY_READ_FULL' => 'full_read',
    'PROPERTY_REPOSITORY' => 'repository',
    'PROPERTY_REFERRAL' => 'referral',
    'PROPERTY_RESOURCE' => 'resource',
    'PROPERTY_RESOURCES' => 'resources',
    'PROPERTY_REFERENCE' => 'reference',
    'PROPERTY_QUALITY' => 'quality',
    'PROPERTY_QUANTITIES' => 'quantities',
    'PROPERTY_RELEASED' => 'released',
    'PROPERTY_RENDER' => 'render',
    'PROPERTY_REPEATABLE' => 'repeatable',
    'PROPERTY_REQUEST' => 'request',
    'PROPERTY_RESULT' => 'result',
    'PROPERTY_RETURN' => 'return',
    'PROPERTY_RESOLUTION' => 'resolution',
    'PROPERTY_RIGHT_MEMBER' => 'right.member',
    'PROPERTY_RIGHT_OPERAND' => 'right.operand',
    'PROPERTY_ROADMAP' => 'roadmap',
    'PROPERTY_ROOT' => 'root',
    'PROPERTY_ROUTE' => 'route',
    'PROPERTY_ROW' => 'row',
    'PROPERTY_ROWS_CALCULATED' => 'calculated_rows',
    'PROPERTY_SCREEN_NAME' => 'screen_name',
    'PROPERTY_SECRET' => 'secret',
    'PROPERTY_SECRET_OAUTH' => 'oauth_secret',
    'PROPERTY_SERIALIZABLE' => 'serializable',
    'PROPERTY_SEQUENCE' => 'sequence',
    'PROPERTY_SETTINGS' => 'settings',
    'PROPERTY_SHORTHAND_ARGUMENTS' => 'args',
    'PROPERTY_SHORTHAND_CONFIGURATION' => 'config',
    'PROPERTY_SIGNAL' => 'signal',
    'PROPERTY_SIGNATURE' => 'signature',
    'PROPERTY_SINCE_ID' => 'since_id',
    'PROPERTY_SIZE' => 'size',
    'PROPERTY_SIZE_COVERAGE' => 'coverage_size',
    'PROPERTY_SORT' => 'sort',
    'PROPERTY_SENDER' => 'sender',
    'PROPERTY_SOURCE' => 'source',
    'PROPERTY_SOURCE_TYPE' => 'source_type',
    'PROPERTY_SPAN' => 'span',
    'PROPERTY_STACK' => 'stack',
    'PROPERTY_START' => 'start',
    'PROPERTY_STATE' => 'state',
    'PROPERTY_STATUS' => 'status',
    'PROPERTY_STORE' => 'store',
    'PROPERTY_STORE_ITEM' => 'store_item',
    'PROPERTY_STREAM_FULL' => 'full_stream',
    'PROPERTY_STREAM' => 'stream',
    'PROPERTY_STRUCTURE' => 'structure',
    'PROPERTY_SUBSEQUENCE' => 'subsequence',
    'PROPERTY_SUBSTREAM' => 'substream',
    'PROPERTY_SUB_FOLDER' => 'sub_folder',
    'PROPERTY_SUB_OPERATIONS' => 'sub_operations',
    'PROPERTY_SUBJECT' => 'subject',
    'PROPERTY_SUBSTITUTION' => 'substitution',
    'PROPERTY_SUBSTITUTIONS' => 'substitutions',
    'PROPERTY_SUBTITLE' => 'subtitle',
    'PROPERTY_SUCCESS' => 'success',
    'PROPERTY_SUMMARY' => 'summary',
    'PROPERTY_SUMMARY_NATIVE' => 'native_summary',
    'PROPERTY_SYNCHRONIZATION' => 'synchronization',
    'PROPERTY_SYNCING' => 'syncing',
    'PROPERTY_TABLE' => 'table',
    'PROPERTY_TABLE_ALIAS' => 'table_alias',
    'PROPERTY_TAG' => 'tag',
    'PROPERTY_TARGET' => 'target',
    'PROPERTY_TARGETS' => 'targets',
    'PROPERTY_TARGET_TYPE' => 'target_type',
    'PROPERTY_TEXT' => 'text',
    'PROPERTY_THREAD' => 'thread',
    'PROPERTY_TIMESTAMP' => 'timestamp',
    'PROPERTY_TITLE' => 'title',
    'PROPERTY_TOKEN' => 'token',
    'PROPERTY_TOKEN_ACCESS' => 'access_token',
    'PROPERTY_TOKEN_OAUTH' => 'oauth_token',
    'PROPERTY_TOKEN_OAUTH_SECRET' => 'oauth_token_secret',
    'PROPERTY_TOKENS' => 'tokens',
    'PROPERTY_TRANSFORMATION' => 'transformation',
    'PROPERTY_TRANSFORMATIONS' => 'transformations',
    'PROPERTY_TYPE' => 'type',
    'PROPERTY_URI_REQUEST' => 'request_uri',
    'PROPERTY_URI' => 'uri',
    'PROPERTY_UNDECLARED' => 'undeclared',
    'PROPERTY_UNDEFINED' => 'undefined',
    'PROPERTY_UNIQUE' => 'unique',
    'PROPERTY_UNLOCKED' => array(
        0 => TRUE,
        1 => 'PROPERTY_RELEASED', 
    ),
    'PROPERTY_USER_NAME' => 'user_name',
    'PROPERTY_VALUE' => 'value',
    'PROPERTY_VALUES' => 'values',
    'PROPERTY_WIDTH' => 'width',
    'PROPERTY_VIEW_BUILDER' => 'view_builder',
    'PROPERTY_WRAPPER' => 'wrapper',
    'PROPERTY_WRAPPERS' => 'wrappers',
    'PROPERTY_YAML_FOLDING_DEPTH' => 10
);

declareConstantsBatch( $properties, FALSE );

/**
*************
* Changes log
*
*************
* 2012 05 05
*************
* 
* Declare following properties
*
*     tokens count
* 
* (revision 905)
*
*************
* 2012 05 08
*************
* 
* Declare following properties
*
*    hashes
*    history
*    methods
*    repeatable
*    source
*    substitution
*    substitutions
*    timestamp
*     transformation
*     transformations
* 
* (revision 911)
*
*************
* 2012 05 09
*************
*
* development :: code generation ::
*
* Declare following properties
*
*    persistency
* 
* (revision 926)
*
*************
* 2012 05 10
*************
*
* development :: code generation ::
* 
* Declare following properties
*
*    files names
*    last occurrence
*    targets
* 
* (revision 927)
*
*/