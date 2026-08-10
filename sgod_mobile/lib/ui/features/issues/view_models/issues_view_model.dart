import 'package:flutter/foundation.dart';

import '../../../../data/models/issue_item.dart';
import '../../../../data/repositories/issues_repository.dart';

/// Manages state for the Issues / Concerns screen: list loading, creating
/// new issues, and deleting existing ones.
class IssuesViewModel extends ChangeNotifier {
  IssuesViewModel(this._repo);

  final IssuesRepository _repo;

  List<IssueItem> _items = const [];
  List<IssueItem> get items => _items;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  bool _isSaving = false;
  bool get isSaving => _isSaving;

  String? _error;
  String? get error => _error;

  /// Year filter — empty means "use server default".
  String _year = '';
  String get year => _year;

  Future<void> load({String? year}) async {
    if (year != null) _year = year;
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _items = await _repo.fetch(year: _year);
    } catch (e) {
      _error = '$e';
      debugPrint('IssuesViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Creates a new issue and prepends it to the list on success.
  Future<bool> addIssue({
    required String title,
    required String description,
    String priority = 'Normal',
    String? year,
  }) async {
    _isSaving = true;
    _error = null;
    notifyListeners();
    try {
      final id = await _repo.create(
        title: title,
        description: description,
        priority: priority,
        year: year ?? _year,
      );
      if (id > 0) {
        // Refresh from the server so we get the canonical row.
        await load();
        return true;
      }
      _error = 'Could not save the issue. Please try again.';
      return false;
    } catch (e) {
      _error = '$e';
      debugPrint('IssuesViewModel.addIssue error: $e');
      return false;
    } finally {
      _isSaving = false;
      notifyListeners();
    }
  }

  /// Deletes an issue by id. Returns true on success.
  Future<bool> deleteIssue(String id) async {
    try {
      final ok = await _repo.delete(id);
      if (ok) {
        _items = _items.where((i) => i.id != id).toList(growable: false);
        notifyListeners();
      }
      return ok;
    } catch (e) {
      _error = '$e';
      debugPrint('IssuesViewModel.deleteIssue error: $e');
      notifyListeners();
      return false;
    }
  }
}
