---
name: book-search-indexing
description: Gunakan skill ini saat memodifikasi fitur pencarian buku, filter katalog, kategori, atau saat mengonfigurasi Laravel Scout dan indexing database perpustakaan.
---

# Book Search & Indexing Rules

1. **Anti N+1 Query**: Selalu gunakan eager loading (with(['authors', 'categories'])) saat mengambil daftar buku untuk halaman katalog.
2. **Database Index**: Jika pencarian menggunakan WHERE clause pada kolom isbn atau 	itle, pastikan membuat composite index melalui migration.
3. **Laravel Scout**: Gunakan driver pencarian yang terkonfigurasi (seperti Meilisearch/Algolia) lewat trait Laravel\\Scout\\Searchable jika pencarian bersifat full-text.
