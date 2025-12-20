# 📚 Elasticsearch Health Check & Troubleshooting Guide
# دليل فحص صحة Elasticsearch وحل المشاكل

---

## 🌐 English Guide

### 1. Check Elasticsearch Server Health

#### 1.1 Basic Connection Test (PowerShell/CMD)

```bash
# Test if server is reachable
curl http://145.223.98.97:9201

# Or using PowerShell
Invoke-RestMethod -Uri "http://145.223.98.97:9201" -Method Get
```

**Expected Response (✅ Working):**
```json
{
  "name" : "elasticsearch-node",
  "cluster_name" : "elasticsearch",
  "cluster_uuid" : "xxxxx",
  "version" : {
    "number" : "7.17.6",
    "build_flavor" : "default"
  },
  "tagline" : "You Know, for Search"
}
```

**If Connection Failed (❌):**
- `Connection refused`: Server is down or port is blocked
- `Timeout`: Network issue or firewall blocking
- `Name resolution failed`: DNS issue

#### 1.2 Check Cluster Health

```bash
curl http://145.223.98.97:9201/_cluster/health
```

| Status | Meaning |
|--------|---------|
| 🟢 `green` | All shards are allocated - Everything OK |
| 🟡 `yellow` | All primary shards OK, some replicas missing |
| 🔴 `red` | Some primary shards are down - Data loss risk! |

#### 1.3 Check Available Indices

```bash
curl "http://145.223.98.97:9201/_cat/indices?v"
```

**Expected Indices:**
- `pages` - Old index (may have issues)
- `pages_new_search` - New optimized index for Arabic

#### 1.4 Test Search Functionality

```bash
# Simple search test
curl "http://145.223.98.97:9201/pages_new_search/_search?q=الصلاة"

# Structured search
curl -X POST "http://145.223.98.97:9201/pages_new_search/_search" \
  -H "Content-Type: application/json" \
  -d '{
    "query": {
      "match": {
        "content.flexible": "الصلاة"
      }
    },
    "size": 5
  }'
```

### 2. Common Problems & Solutions

#### Problem 1: Connection Refused

**Symptoms:**
- `Connection refused` error
- `curl: (7) Failed to connect`

**Solutions:**
1. Check if Elasticsearch is running:
   ```bash
   # On the server
   systemctl status elasticsearch
   ```
2. Check firewall rules:
   ```bash
   # Allow port 9201
   ufw allow 9201
   ```
3. Verify host and port in `.env`:
   ```env
   ELASTICSEARCH_HOST=http://145.223.98.97:9201
   ```

#### Problem 2: Search Returns 0 Results

**Symptoms:**
- API returns `{"total": 0}`
- Search for Arabic words finds nothing

**Solutions:**
1. Check if index has documents:
   ```bash
   curl "http://145.223.98.97:9201/pages_new_search/_count"
   ```
2. Verify correct index is being used
3. Check if analyzers are properly configured (see analyzer test below)

#### Problem 3: Slow Search Performance

**Symptoms:**
- Search takes > 500ms
- Timeouts on complex queries

**Solutions:**
1. Check deleted documents ratio:
   ```bash
   curl "http://145.223.98.97:9201/_cat/segments?v"
   ```
2. Run force merge if needed:
   ```bash
   curl -X POST "http://145.223.98.97:9201/pages_new_search/_forcemerge?max_num_segments=1"
   ```
3. Increase timeout in config

#### Problem 4: Index Missing

**Symptoms:**
- `index_not_found_exception`
- 404 error when accessing index

**Solutions:**
1. List all indices:
   ```bash
   curl "http://145.223.98.97:9201/_cat/indices?v"
   ```
2. Create index if missing (run the setup script)
3. Re-index data using Scout

### 3. PHP Connection Test

Create a file `test-elasticsearch.php`:

```php
<?php

require 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$host = 'http://145.223.98.97:9201';

echo "🔍 Testing Elasticsearch Connection...\n\n";

try {
    $client = ClientBuilder::create()
        ->setHosts([$host])
        ->setConnectionPool('\Elasticsearch\ConnectionPool\StaticNoPingConnectionPool')
        ->setRetries(1)
        ->setSSLVerification(false)
        ->build();

    // Test ping
    $response = $client->ping();
    echo "✅ Connection successful!\n";

    // Get cluster info
    $info = $client->info();
    echo "📊 Cluster: {$info['cluster_name']}\n";
    echo "🏷️ Version: {$info['version']['number']}\n\n";

    // Check index exists
    $index = 'pages_new_search';
    if ($client->indices()->exists(['index' => $index])) {
        echo "✅ Index '$index' exists\n";
        
        // Get document count
        $count = $client->count(['index' => $index]);
        echo "📚 Documents: {$count['count']}\n";
        
        // Test simple search
        $result = $client->search([
            'index' => $index,
            'body' => [
                'query' => ['match_all' => new \stdClass()],
                'size' => 1
            ]
        ]);
        echo "🔍 Test search: Found {$result['hits']['total']['value']} documents\n";
    } else {
        echo "❌ Index '$index' NOT found\n";
    }

} catch (Exception $e) {
    echo "❌ Connection FAILED!\n";
    echo "Error: {$e->getMessage()}\n";
}
```

Run with:
```bash
php test-elasticsearch.php
```

---

## 🌍 الدليل العربي

### 1. فحص صحة خادم Elasticsearch

#### 1.1 اختبار الاتصال الأساسي

```bash
# اختبار الوصول للخادم
curl http://145.223.98.97:9201

# أو باستخدام PowerShell
Invoke-RestMethod -Uri "http://145.223.98.97:9201" -Method Get
```

**الاستجابة المتوقعة (✅ يعمل):**
```json
{
  "name" : "elasticsearch-node",
  "cluster_name" : "elasticsearch",
  "version" : {
    "number" : "7.17.6"
  },
  "tagline" : "You Know, for Search"
}
```

**إذا فشل الاتصال (❌):**
- `Connection refused`: الخادم متوقف أو المنفذ محجوب
- `Timeout`: مشكلة في الشبكة أو جدار الحماية
- `Name resolution failed`: مشكلة في DNS

#### 1.2 فحص صحة الكلاستر

```bash
curl http://145.223.98.97:9201/_cluster/health
```

| الحالة | المعنى |
|--------|---------|
| 🟢 `green` | جميع الـ Shards موزعة - كل شيء ممتاز |
| 🟡 `yellow` | الـ Shards الأساسية تعمل، بعض النسخ غير متوفرة |
| 🔴 `red` | بعض الـ Shards الأساسية متوقفة - خطر فقدان البيانات! |

#### 1.3 فحص الفهارس المتاحة

```bash
curl "http://145.223.98.97:9201/_cat/indices?v"
```

**الفهارس المتوقعة:**
- `pages` - الفهرس القديم (قد يكون به مشاكل)
- `pages_new_search` - الفهرس الجديد المحسّن للعربية

#### 1.4 اختبار البحث

```bash
# اختبار بحث بسيط
curl "http://145.223.98.97:9201/pages_new_search/_search?q=الصلاة"

# بحث منظم
curl -X POST "http://145.223.98.97:9201/pages_new_search/_search" \
  -H "Content-Type: application/json" \
  -d '{
    "query": {
      "match": {
        "content.flexible": "الصلاة"
      }
    },
    "size": 5
  }'
```

### 2. المشاكل الشائعة والحلول

#### المشكلة 1: رفض الاتصال

**الأعراض:**
- خطأ `Connection refused`
- `curl: (7) Failed to connect`

**الحلول:**
1. تحقق من تشغيل Elasticsearch:
   ```bash
   systemctl status elasticsearch
   ```
2. تحقق من قواعد جدار الحماية
3. تأكد من صحة المضيف والمنفذ في `.env`

#### المشكلة 2: البحث يرجع 0 نتائج

**الأعراض:**
- API يرجع `{"total": 0}`
- البحث عن كلمات عربية لا يجد شيء

**الحلول:**
1. تحقق من وجود مستندات في الفهرس
2. تأكد من استخدام الفهرس الصحيح
3. تأكد من إعداد المحللات بشكل صحيح

#### المشكلة 3: بطء البحث

**الأعراض:**
- البحث يستغرق أكثر من 500 مللي ثانية
- timeout على الاستعلامات المعقدة

**الحلول:**
1. فحص نسبة المستندات المحذوفة
2. تنفيذ force merge إذا لزم الأمر
3. زيادة الـ timeout في الإعدادات

### 3. أوامر الفحص السريع

```bash
# 1. فحص الاتصال
curl http://145.223.98.97:9201

# 2. فحص صحة الكلاستر
curl http://145.223.98.97:9201/_cluster/health?pretty

# 3. قائمة الفهارس
curl http://145.223.98.97:9201/_cat/indices?v

# 4. عدد المستندات
curl http://145.223.98.97:9201/pages_new_search/_count

# 5. اختبار البحث
curl "http://145.223.98.97:9201/pages_new_search/_search?q=content:الصلاة&size=3"

# 6. فحص الإعدادات
curl http://145.223.98.97:9201/pages_new_search/_settings?pretty

# 7. فحص الـ Mapping
curl http://145.223.98.97:9201/pages_new_search/_mapping?pretty
```

---

## 📋 Checklist for Quick Health Check

### ✅ English Checklist

- [ ] Server is reachable: `curl http://145.223.98.97:9201`
- [ ] Cluster status is green/yellow: `/_cluster/health`
- [ ] Index exists: `/_cat/indices?v`
- [ ] Documents are indexed: `/_count`
- [ ] Search returns results: `/_search?q=test`
- [ ] Analyzers are working: `/_analyze` endpoint
- [ ] `.env` has correct ELASTICSEARCH_HOST

### ✅ قائمة الفحص بالعربي

- [ ] الخادم قابل للوصول
- [ ] حالة الكلاستر أخضر/أصفر
- [ ] الفهرس موجود
- [ ] المستندات مفهرسة
- [ ] البحث يرجع نتائج
- [ ] المحللات تعمل
- [ ] `.env` يحتوي عنوان صحيح

---

## 🔗 Related Documentation

- [Elasticsearch Implementation Plan](file:///c:/Users/osaidsalah002/Documents/BMS_alkamelah/docs/ELASTICSEARCH_HOW_IT_WORKS.md)
- [How Elasticsearch Works in This Project](file:///c:/Users/osaidsalah002/Documents/BMS_alkamelah/docs/ELASTICSEARCH_IN_PROJECT.md)
- [Search Dynamic Implementation Plan](file:///C:/Users/osaidsalah002/.gemini/antigravity/brain/1dbe0afa-6ed1-40fc-8e69-08cc558cd311/implementation_plan.md)
