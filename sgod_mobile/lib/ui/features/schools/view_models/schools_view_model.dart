import 'package:flutter/foundation.dart';

import '../../../../data/models/school_item.dart';
import '../../../../data/repositories/schools_repository.dart';

/// Manages schools list state — loads data from the API, supports
/// client-side search/filtering, and exposes items grouped by district.
class SchoolsViewModel extends ChangeNotifier {
  SchoolsViewModel(this._repo);

  final SchoolsRepository _repo;

  List<SchoolItem> _items = [];
  List<SchoolItem> get items => _items;

  /// Items after the current search query has been applied.
  List<SchoolItem> get filteredItems =>
      _repo.search(_items, _searchQuery);

  String _searchQuery = '';
  String get searchQuery => _searchQuery;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  /// Total count of all loaded schools (before filtering).
  int get totalCount => _items.length;

  /// Loads schools from the API. Pass [silent] to skip the loading indicator
  /// (used by pull-to-refresh).
  Future<void> load({bool silent = false}) async {
    if (!silent) {
      _isLoading = true;
      _error = null;
      notifyListeners();
    }
    try {
      _items = await _repo.fetch(limit: 100, offset: 0);
    } catch (e) {
      _error = '$e';
      debugPrint('SchoolsViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Updates the search query and notifies listeners so the view re-filters.
  void search(String query) {
    _searchQuery = query;
    notifyListeners();
  }

  /// Clears the current search filter.
  void clearSearch() {
    _searchQuery = '';
    notifyListeners();
  }
}
