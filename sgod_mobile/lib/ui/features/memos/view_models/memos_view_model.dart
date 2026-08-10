import 'package:flutter/foundation.dart';

import '../../../../data/models/memo_item.dart';
import '../../../../data/repositories/memos_repository.dart';

/// Manages memos list state — loads data from the API, handles loading/
/// error states, supports pull-to-refresh, search filtering, and
/// pagination (page-based, 25 items per page).
class MemosViewModel extends ChangeNotifier {
  MemosViewModel(this._repo);

  final MemosRepository _repo;

  static const int pageSize = 25;

  List<MemoItem> _items = const [];
  List<MemoItem> get items => _filtered.take(_currentPage * pageSize).toList();

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  bool _isLoadingMore = false;
  bool get isLoadingMore => _isLoadingMore;

  bool get hasMore => _filtered.length > _currentPage * pageSize;

  String? _error;
  String? get error => _error;

  String _query = '';
  String get query => _query;

  int _currentPage = 1;
  int get currentPage => _currentPage;

  int get totalCount => _filtered.length;

  set query(String value) {
    if (_query == value) return;
    _query = value;
    _currentPage = 1;
    notifyListeners();
  }

  /// The full unfiltered list.
  List<MemoItem> get all => _items;

  List<MemoItem> get _filtered {
    final q = _query.trim().toLowerCase();
    if (q.isEmpty) return _items;
    return _items.where((m) {
      return m.title.toLowerCase().contains(q) ||
          m.memoNo.toLowerCase().contains(q);
    }).toList();
  }

  Future<void> load() async {
    _isLoading = true;
    _error = null;
    _currentPage = 1;
    notifyListeners();
    try {
      _items = await _repo.fetch(limit: 200);
    } catch (e) {
      _error = '$e';
      debugPrint('MemosViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  void loadMore() {
    if (_isLoadingMore || !hasMore) return;
    _isLoadingMore = true;
    notifyListeners();
    // Simulate async for smooth UX — data is already loaded in memory
    Future.delayed(const Duration(milliseconds: 200), () {
      _currentPage++;
      _isLoadingMore = false;
      notifyListeners();
    });
  }
}
