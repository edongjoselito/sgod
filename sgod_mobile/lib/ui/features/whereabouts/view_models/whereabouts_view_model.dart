import 'package:flutter/foundation.dart';

import '../../../../data/models/whereabouts_item.dart';
import '../../../../data/repositories/whereabouts_repository.dart';

/// Manages whereabouts list state — loads data from the API and exposes
/// loading/error states for the view.
class WhereaboutsViewModel extends ChangeNotifier {
  WhereaboutsViewModel(this._repo);

  final WhereaboutsRepository _repo;

  List<WhereaboutsItem> _items = [];
  List<WhereaboutsItem> get items => _items;

  /// Items after the current search query has been applied.
  List<WhereaboutsItem> get filteredItems => _search(_items, _searchQuery);

  String _searchQuery = '';
  String get searchQuery => _searchQuery;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  /// Loads whereabouts from the API. Pass [silent] to skip the loading
  /// indicator (used by pull-to-refresh).
  Future<void> load({bool silent = false}) async {
    if (!silent) {
      _isLoading = true;
      _error = null;
      notifyListeners();
    }
    try {
      _items = await _repo.fetch(limit: 50, offset: 0);
    } catch (e) {
      _error = '$e';
      debugPrint('WhereaboutsViewModel.load error: $e');
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

  /// Client-side search across name, activity, and location.
  List<WhereaboutsItem> _search(List<WhereaboutsItem> items, String query) {
    final q = query.trim().toLowerCase();
    if (q.isEmpty) return items;
    return items.where((w) {
      final name = '${w.fName} ${w.lName}'.toLowerCase();
      return name.contains(q) ||
          w.activity.toLowerCase().contains(q) ||
          w.location.toLowerCase().contains(q);
    }).toList();
  }
}
